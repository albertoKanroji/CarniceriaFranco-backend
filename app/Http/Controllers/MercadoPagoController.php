<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\SDK;
use MercadoPago\Payment;
use MercadoPago\Preference;
use MercadoPago\Item;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        // Configurar Mercado Pago con el access token
        SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
    }

    /**
     * Crear una preferencia de pago para Mercado Pago
     */
    public function createPreference(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|exists:customers,id',
                'productos' => 'required|array|min:1',
                'productos.*.product_id' => 'required|exists:products,id',
                'productos.*.cantidad' => 'required|numeric|min:0.01',
                'descuento' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'status' => 422,
                    'message' => 'Error de validación',
                    'data' => $validator->errors()
                ], 422);
            }

            $preference = new Preference();
            $items = [];
            $totalAmount = 0;

            // Crear items para Mercado Pago
            foreach ($request->productos as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || !$product->activo) {
                    throw new \Exception("Producto no disponible");
                }

                if ($product->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$product->nombre}");
                }

                // Calcular precio final (con oferta si aplica)
                $precioFinal = $product->en_oferta && $product->precio_oferta
                    ? $product->precio_oferta
                    : $product->precio;

                $mpItem = new Item();
                $mpItem->id = $product->id;
                $mpItem->title = $product->nombre;
                $mpItem->quantity = (int)$item['cantidad'];
                $mpItem->unit_price = (float)$precioFinal;
                $mpItem->currency_id = 'MXN';
                $mpItem->description = $product->descripcion ?? $product->nombre;

                if ($product->imagen_url) {
                    $mpItem->picture_url = $product->imagen_url;
                }

                $items[] = $mpItem;
                $totalAmount += $precioFinal * $item['cantidad'];
            }

            // Aplicar descuento si existe
            if ($request->descuento && $request->descuento > 0) {
                $totalAmount -= $request->descuento;
            }

            $preference->items = $items;

            // Configurar URLs de redirección
            $preference->back_urls = [
                "success" => env('APP_URL') . "/api/v1/mercadopago/success",
                "failure" => env('APP_URL') . "/api/v1/mercadopago/failure",
                "pending" => env('APP_URL') . "/api/v1/mercadopago/pending"
            ];

            $preference->auto_return = "approved";

            // Configurar datos adicionales
            $preference->external_reference = "SALE_" . uniqid() . "_CUSTOMER_" . $request->customer_id;

            // Configurar notificaciones webhook
            $preference->notification_url = env('APP_URL') . "/api/v1/mercadopago/webhook";

            // Configurar datos del pagador
            $customer = \App\Models\Customers::find($request->customer_id);
            if ($customer) {
                $preference->payer = [
                    "name" => $customer->nombre,
                    "email" => $customer->email ?? 'customer@example.com',
                    "phone" => [
                        "area_code" => "52",
                        "number" => $customer->telefono ?? "1234567890"
                    ]
                ];
            }

            // Metadata adicional
            $preference->metadata = [
                'customer_id' => $request->customer_id,
                'productos' => json_encode($request->productos),
                'descuento' => $request->descuento ?? 0,
                'notas' => $request->notas ?? null
            ];

            $preference->save();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Preferencia de pago creada correctamente',
                'data' => [
                    'preference_id' => $preference->id,
                    'init_point' => $preference->init_point,
                    'sandbox_init_point' => $preference->sandbox_init_point,
                    'total_amount' => $totalAmount,
                    'external_reference' => $preference->external_reference
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error creating MercadoPago preference: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear preferencia de pago: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Webhook para recibir notificaciones de Mercado Pago
     */
    public function webhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('MercadoPago webhook received:', $data);

            if (isset($data['type']) && $data['type'] === 'payment') {
                $paymentId = $data['data']['id'];

                // Obtener información del pago
                $payment = Payment::find_by_id($paymentId);

                if ($payment) {
                    Log::info('Payment details:', [
                        'id' => $payment->id,
                        'status' => $payment->status,
                        'external_reference' => $payment->external_reference
                    ]);

                    // Procesar el pago según su estado
                    switch ($payment->status) {
                        case 'approved':
                            $this->processApprovedPayment($payment);
                            break;
                        case 'pending':
                            $this->processPendingPayment($payment);
                            break;
                        case 'rejected':
                            $this->processRejectedPayment($payment);
                            break;
                    }
                }
            }

            return response()->json(['status' => 'ok'], 200);

        } catch (\Exception $e) {
            Log::error('Error processing MercadoPago webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Procesar pago aprobado
     */
    private function processApprovedPayment($payment)
    {
        try {
            // Extraer información de la referencia externa
            $externalReference = $payment->external_reference;
            $parts = explode('_', $externalReference);

            if (count($parts) >= 4) {
                $customerId = $parts[3];

                // Obtener metadata del pago para reconstruir la venta
                $metadata = $payment->metadata;

                if ($metadata && isset($metadata->customer_id)) {
                    // Crear la venta usando el SalesController
                    $salesController = new SalesController();

                    $requestData = [
                        'customer_id' => $metadata->customer_id,
                        'productos' => json_decode($metadata->productos, true),
                        'descuento' => $metadata->descuento ?? 0,
                        'notas' => ($metadata->notas ?? '') . " | Pago MP: {$payment->id}",
                        'metodo_pago' => 'mercado_pago',
                        'mercadopago_payment_id' => $payment->id,
                        'mercadopago_status' => $payment->status
                    ];

                    $request = new Request($requestData);
                    $response = $salesController->store($request);

                    Log::info('Sale created from MercadoPago payment', [
                        'payment_id' => $payment->id,
                        'sale_response' => $response->getData()
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Error processing approved payment: ' . $e->getMessage());
        }
    }

    /**
     * Procesar pago pendiente
     */
    private function processPendingPayment($payment)
    {
        Log::info('Payment pending:', ['payment_id' => $payment->id]);
        // Aquí puedes implementar lógica para pagos pendientes
    }

    /**
     * Procesar pago rechazado
     */
    private function processRejectedPayment($payment)
    {
        Log::info('Payment rejected:', ['payment_id' => $payment->id]);
        // Aquí puedes implementar lógica para pagos rechazados
    }

    /**
     * Manejar redirección de éxito
     */
    public function success(Request $request)
    {
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');

        return response()->json([
            'success' => true,
            'message' => 'Pago procesado correctamente',
            'payment_id' => $paymentId,
            'status' => $status
        ]);
    }

    /**
     * Manejar redirección de fallo
     */
    public function failure(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'El pago no pudo ser procesado'
        ]);
    }

    /**
     * Manejar redirección de pendiente
     */
    public function pending(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'El pago está pendiente de confirmación'
        ]);
    }
}

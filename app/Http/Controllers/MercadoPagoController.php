<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\SDK;
use MercadoPago\Payment;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        // Verificar que las credenciales existan
        $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');

        if (!$accessToken) {
            throw new \Exception('MERCADOPAGO_ACCESS_TOKEN no configurado en .env');
        }

        // Configurar SDK de MercadoPago
        SDK::setAccessToken($accessToken);

        Log::info('MercadoPago SDK initialized', [
            'token_prefix' => substr($accessToken, 0, 10) . '...'
        ]);
    }

    /**
     * Crear preferencia de pago - VERSIÓN CORREGIDA
     */
    public function createPreference(Request $request)
    {
        try {
            // Validación básica
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|exists:customers,id',
                'productos' => 'required|array|min:1',
                'productos.*.product_id' => 'required|exists:products,id',
                'productos.*.cantidad' => 'nullable|numeric|min:0.01',
                'productos.*.monto_pesos' => 'nullable|numeric|min:0',
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

            // Validar que cada producto tenga cantidad O monto_pesos
            foreach ($request->productos as $index => $item) {
                if (empty($item['cantidad']) && empty($item['monto_pesos'])) {
                    return response()->json([
                        'success' => false,
                        'status' => 422,
                        'message' => 'Cada producto debe tener cantidad o monto_pesos'
                    ], 422);
                }
            }

            // Configurar SDK
            $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');
            SDK::setAccessToken($accessToken);

            // Crear preferencia
            $preference = new Preference();
            $items = [];
            $totalAmount = 0;

            // Procesar productos
            foreach ($request->productos as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || !$product->activo) {
                    return response()->json([
                        'success' => false,
                        'status' => 404,
                        'message' => "Producto no disponible: ID {$item['product_id']}"
                    ], 404);
                }

                // Precio unitario (con ofertas)
                $precioUnitario = ($product->en_oferta && $product->precio_oferta)
                    ? $product->precio_oferta
                    : $product->precio;

                // Calcular según tipo de venta
                if (!empty($item['monto_pesos'])) {
                    // Venta por monto
                    $monto = floatval($item['monto_pesos']);
                    $precioTotal = $monto;
                } else {
                    // Venta por cantidad
                    $cantidad = floatval($item['cantidad']);
                    $precioTotal = $cantidad * $precioUnitario;

                    // Verificar stock
                    if ($product->stock < $cantidad) {
                        return response()->json([
                            'success' => false,
                            'status' => 409,
                            'message' => "Stock insuficiente para {$product->nombre}"
                        ], 409);
                    }
                }

                // Crear item para MP (usando el patrón que funciona)
                $mpItem = new Item();
                $mpItem->title = $product->nombre;
                $mpItem->quantity = 1;
                $mpItem->unit_price = round($precioTotal, 2);
                $mpItem->currency_id = 'MXN';

                $items[] = $mpItem;
                $totalAmount += $precioTotal;
            }

            // Aplicar descuento
            if ($request->descuento && $request->descuento > 0) {
                $totalAmount -= $request->descuento;
            }

            // Configurar preferencia (igual que el test que funciona)
            $preference->items = $items;

            $preference->back_urls = [
                "success" => "http://localhost/api/v1/mercadopago/success",
                "failure" => "http://localhost/api/v1/mercadopago/failure",
                "pending" => "http://localhost/api/v1/mercadopago/pending"
            ];

            $preference->external_reference = 'CARNICERIA_' . time() . '_CUSTOMER_' . $request->customer_id;

            Log::info('Creating MercadoPago preference:', [
                'items_count' => count($items),
                'total' => $totalAmount,
                'reference' => $preference->external_reference
            ]);

            // Guardar preferencia
            $preference->save();

            Log::info('MercadoPago preference result:', [
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point
            ]);

            // Verificar resultado
            if (!$preference->id) {
                return response()->json([
                    'success' => false,
                    'status' => 500,
                    'message' => 'No se pudo crear la preferencia de pago'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Preferencia de pago creada correctamente',
                'data' => [
                    'preference_id' => $preference->id,
                    'init_point' => $preference->init_point,
                    'sandbox_init_point' => $preference->sandbox_init_point,
                    'total_amount' => round($totalAmount, 2),
                    'external_reference' => $preference->external_reference
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating MercadoPago preference: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error interno: ' . $e->getMessage()
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

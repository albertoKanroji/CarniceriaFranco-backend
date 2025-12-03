<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    /**
     * Crear una nueva venta (compra del cliente)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,credito',
            'productos' => 'required|array|min:1',
            'productos.*.product_id' => 'required|exists:products,id',
            'productos.*.cantidad' => 'required|numeric|min:0.01',
            'descuento' => 'nullable|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => 'Error de validación',
                'data' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $detalles = [];

            // Validar stock y calcular totales
            foreach ($request->productos as $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    throw new \Exception("Producto con ID {$item['product_id']} no encontrado");
                }

                if (!$product->activo) {
                    throw new \Exception("El producto {$product->nombre} no está disponible");
                }

                if ($product->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$product->nombre}. Disponible: {$product->stock}");
                }

                // Calcular precio final (con oferta si aplica)
                $precioUnitario = $product->precio;
                $precioOferta = $product->en_oferta ? $product->precio_oferta : null;
                $precioFinal = $precioOferta ?? $precioUnitario;
                $itemSubtotal = $precioFinal * $item['cantidad'];

                $subtotal += $itemSubtotal;

                $detalles[] = [
                    'product' => $product,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $precioUnitario,
                    'precio_oferta' => $precioOferta,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Calcular descuento e impuestos
            $descuento = $request->descuento ?? 0;
            $impuestos = ($subtotal - $descuento) * 0.16; // 16% IVA
            $total = $subtotal - $descuento + $impuestos;

            // Crear la venta
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'fecha_venta' => now(),
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'impuestos' => $impuestos,
                'total' => $total,
                'metodo_pago' => $request->metodo_pago,
                'estatus' => 'completada',
                'notas' => $request->notas,
            ]);

            // Crear detalles y actualizar stock
            foreach ($detalles as $detalle) {
                $product = $detalle['product'];

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'precio_oferta' => $detalle['precio_oferta'],
                    'descuento' => 0,
                    'subtotal' => $detalle['subtotal'],
                    'total' => $detalle['subtotal'],
                    'producto_nombre' => $product->nombre,
                    'producto_codigo' => $product->codigo,
                    'unidad_venta' => $product->unidad_venta,
                ]);

                // Actualizar stock del producto
                $product->stock -= $detalle['cantidad'];
                $product->save();
            }

            // Actualizar estadísticas del cliente
            $customer = Customers::find($request->customer_id);
            $customer->total_compras = ($customer->total_compras ?? 0) + $total;
            $customer->numero_compras = ($customer->numero_compras ?? 0) + 1;
            $customer->fecha_ultima_compra = now();
            $customer->save();

            DB::commit();

            // Cargar relaciones para respuesta
            $sale->load(['details', 'customer']);

            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Compra realizada exitosamente',
                'data' => $sale
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al procesar la compra: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener historial de compras del cliente
     */
    public function getCustomerPurchases($customerId)
    {
        try {
            $customer = Customers::find($customerId);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'Cliente no encontrado',
                    'data' => null
                ], 404);
            }

            $purchases = Sale::with(['details.product'])
                ->where('customer_id', $customerId)
                ->orderBy('fecha_venta', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Historial obtenido correctamente',
                'data' => [
                    'customer' => $customer,
                    'purchases' => $purchases
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener historial: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener detalle de una compra específica
     */
    public function getPurchaseDetail($saleId)
    {
        try {
            $sale = Sale::with(['details.product', 'customer'])
                ->find($saleId);

            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'Compra no encontrada',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Detalle de compra obtenido correctamente',
                'data' => $sale
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener detalle: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de compras del cliente
     */
    public function getCustomerStats($customerId)
    {
        try {
            $customer = Customers::find($customerId);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'Cliente no encontrado',
                    'data' => null
                ], 404);
            }

            $stats = [
                'total_compras' => $customer->total_compras ?? 0,
                'numero_compras' => $customer->numero_compras ?? 0,
                'fecha_ultima_compra' => $customer->fecha_ultima_compra,
                'promedio_compra' => $customer->numero_compras > 0
                    ? ($customer->total_compras / $customer->numero_compras)
                    : 0,
                'compras_este_mes' => Sale::where('customer_id', $customerId)
                    ->whereMonth('fecha_venta', now()->month)
                    ->whereYear('fecha_venta', now()->year)
                    ->count(),
                'total_este_mes' => Sale::where('customer_id', $customerId)
                    ->whereMonth('fecha_venta', now()->month)
                    ->whereYear('fecha_venta', now()->year)
                    ->sum('total'),
                'producto_mas_comprado' => $this->getTopProduct($customerId),
            ];

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estadísticas obtenidas correctamente',
                'data' => $stats
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener últimas compras del cliente (más recientes)
     */
    public function getRecentPurchases($customerId, $limit = 5)
    {
        try {
            $purchases = Sale::with(['details.product'])
                ->where('customer_id', $customerId)
                ->orderBy('fecha_venta', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Últimas compras obtenidas correctamente',
                'data' => $purchases
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener compras recientes: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Cancelar una compra
     */
    public function cancelPurchase($saleId)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::with('details')->find($saleId);

            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'Compra no encontrada',
                    'data' => null
                ], 404);
            }

            if ($sale->estatus == 'cancelada') {
                return response()->json([
                    'success' => false,
                    'status' => 400,
                    'message' => 'La compra ya está cancelada',
                    'data' => null
                ], 400);
            }

            // Devolver stock a los productos
            foreach ($sale->details as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->stock += $detail->cantidad;
                    $product->save();
                }
            }

            // Actualizar estadísticas del cliente
            $customer = Customers::find($sale->customer_id);
            $customer->total_compras -= $sale->total;
            $customer->numero_compras -= 1;
            $customer->save();

            // Cambiar estatus de la venta
            $sale->estatus = 'cancelada';
            $sale->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Compra cancelada exitosamente',
                'data' => $sale
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al cancelar compra: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Método privado para obtener el producto más comprado
     */
    private function getTopProduct($customerId)
    {
        $topProduct = SaleDetail::select('product_id', 'producto_nombre', DB::raw('SUM(cantidad) as total_cantidad'))
            ->whereHas('sale', function($query) use ($customerId) {
                $query->where('customer_id', $customerId)
                      ->where('estatus', '!=', 'cancelada');
            })
            ->groupBy('product_id', 'producto_nombre')
            ->orderBy('total_cantidad', 'desc')
            ->first();

        return $topProduct ? [
            'producto' => $topProduct->producto_nombre,
            'cantidad_total' => $topProduct->total_cantidad
        ] : null;
    }

    /**
     * Obtener todas las ventas (para admin)
     */
    public function getAllSales(Request $request)
    {
        try {
            $query = Sale::with(['customer', 'details']);

            // Filtrar por estatus
            if ($request->has('estatus')) {
                $query->where('estatus', $request->estatus);
            }

            // Filtrar por rango de fechas
            if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
                $query->entreFechas($request->fecha_inicio, $request->fecha_fin);
            }

            // Filtrar por método de pago
            if ($request->has('metodo_pago')) {
                $query->porMetodoPago($request->metodo_pago);
            }

            $sales = $query->orderBy('fecha_venta', 'desc')
                ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Ventas obtenidas correctamente',
                'data' => $sales
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener ventas: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

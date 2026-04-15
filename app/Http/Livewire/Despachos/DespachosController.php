<?php

namespace App\Http\Livewire\Despachos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Customers;
use Carbon\Carbon;
use App\Services\OrderNotificationService;
use Illuminate\Support\Facades\DB;

class DespachosController extends Component
{
    use WithPagination;

    public $pageTitle, $componentName;
    public $selectedSaleId = null;
    public $saleDetails = [];
    public $search = '';
    public $filtroEstado = '';
    public $filtroCliente = '';
    public $filtroFolio = '';
    public $updatingDetailId = null;

    // Crear pedido
    public $createCustomerId = '';
    public $createMetodoPago = 'efectivo';
    public $createNotas = '';
    public $createDescuento = 0;
    public $productSearch = '';
    public $cart = [];

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refreshDespachos' => '$refresh',
        'closeModal' => 'closeModal',
        'closeCreateOrderModal' => 'closeCreateOrderModal'
    ];

    public function mount()
    {
        $this->pageTitle = 'Gestión';
        $this->componentName = 'Despachos';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingFiltroCliente()
    {
        $this->resetPage();
    }

    public function updatingFiltroFolio()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filtroEstado = '';
        $this->filtroCliente = '';
        $this->filtroFolio = '';
        $this->search = '';
        $this->resetPage();
    }

    public function openCreateOrderModal()
    {
        $this->resetCreateOrderForm();
        $this->emit('show-create-order-modal');
    }

    public function closeCreateOrderModal()
    {
        $this->resetCreateOrderForm();
    }

    public function addProductToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || !$product->activo) {
            $this->emit('despacho-error', 'Producto no disponible');
            return;
        }

        $currentQty = isset($this->cart[$productId]) ? (float) $this->cart[$productId]['cantidad'] : 0;
        $newQty = $currentQty + 1;

        if ($newQty > (float) $product->stock) {
            $this->emit('despacho-error', 'Stock insuficiente para ' . $product->nombre);
            return;
        }

        $precioUnitario = (float) $product->precio;
        $precioOferta = $product->en_oferta ? (float) $product->precio_oferta : null;
        $precioFinal = $precioOferta ?? $precioUnitario;

        $this->cart[$productId] = [
            'product_id' => $product->id,
            'codigo' => $product->codigo,
            'nombre' => $product->nombre,
            'unidad_venta' => $product->unidad_venta,
            'cantidad' => $newQty,
            'stock' => (float) $product->stock,
            'precio_unitario' => $precioUnitario,
            'precio_oferta' => $precioOferta,
            'precio_final' => $precioFinal,
        ];
    }

    public function increaseQty($productId)
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        $newQty = (float) $this->cart[$productId]['cantidad'] + 1;
        if ($newQty > (float) $this->cart[$productId]['stock']) {
            $this->emit('despacho-error', 'Stock insuficiente para ' . $this->cart[$productId]['nombre']);
            return;
        }

        $this->cart[$productId]['cantidad'] = $newQty;
    }

    public function decreaseQty($productId)
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        $newQty = (float) $this->cart[$productId]['cantidad'] - 1;
        if ($newQty <= 0) {
            unset($this->cart[$productId]);
            return;
        }

        $this->cart[$productId]['cantidad'] = $newQty;
    }

    public function updateQty($productId, $value)
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        $qty = (float) $value;
        if ($qty <= 0) {
            unset($this->cart[$productId]);
            return;
        }

        if ($qty > (float) $this->cart[$productId]['stock']) {
            $this->emit('despacho-error', 'Stock insuficiente para ' . $this->cart[$productId]['nombre']);
            $this->cart[$productId]['cantidad'] = (float) $this->cart[$productId]['stock'];
            return;
        }

        $this->cart[$productId]['cantidad'] = $qty;
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function createOrder()
    {
        if (!$this->createCustomerId) {
            $this->emit('despacho-error', 'Selecciona un cliente');
            return;
        }

        if (count($this->cart) === 0) {
            $this->emit('despacho-error', 'Agrega productos al carrito');
            return;
        }

        if (!in_array($this->createMetodoPago, ['efectivo', 'tarjeta', 'transferencia', 'credito'], true)) {
            $this->emit('despacho-error', 'Metodo de pago no valido');
            return;
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $detalles = [];

            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || !$product->activo) {
                    throw new \Exception('Producto no disponible: ' . ($item['nombre'] ?? 'N/A'));
                }

                $qty = (float) $item['cantidad'];
                if ($qty <= 0) {
                    throw new \Exception('Cantidad invalida para ' . $product->nombre);
                }

                if ((float) $product->stock < $qty) {
                    throw new \Exception('Stock insuficiente para ' . $product->nombre . '. Disponible: ' . $product->stock);
                }

                $precioUnitario = (float) $product->precio;
                $precioOferta = $product->en_oferta ? (float) $product->precio_oferta : null;
                $precioFinal = $precioOferta ?? $precioUnitario;
                $itemSubtotal = $precioFinal * $qty;

                $subtotal += $itemSubtotal;

                $detalles[] = [
                    'product' => $product,
                    'cantidad' => $qty,
                    'precio_unitario' => $precioUnitario,
                    'precio_oferta' => $precioOferta,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $descuento = max(0, (float) ($this->createDescuento ?? 0));
            if ($descuento > $subtotal) {
                $descuento = $subtotal;
            }

            $impuestos = ($subtotal - $descuento) * 0.16;
            $total = $subtotal - $descuento + $impuestos;

            $sale = Sale::create([
                'customer_id' => $this->createCustomerId,
                'fecha_venta' => now(),
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'impuestos' => $impuestos,
                'total' => $total,
                'metodo_pago' => $this->createMetodoPago,
                'estatus' => 'completada',
                'notas' => $this->createNotas,
                'estado_envio' => 'Pendiente',
                'usuario_id' => auth()->id(),
            ]);

            foreach ($detalles as $detalle) {
                $product = $detalle['product'];

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'cantidad' => $detalle['cantidad'],
                    'monto_pesos' => null,
                    'precio_unitario' => $detalle['precio_unitario'],
                    'precio_oferta' => $detalle['precio_oferta'],
                    'descuento' => 0,
                    'subtotal' => $detalle['subtotal'],
                    'total' => $detalle['subtotal'],
                    'producto_nombre' => $product->nombre,
                    'producto_codigo' => $product->codigo,
                    'unidad_venta' => $product->unidad_venta,
                    'estado_despacho' => 0,
                ]);

                $product->decrement('stock', $detalle['cantidad']);
            }

            $customer = Customers::find($this->createCustomerId);
            if ($customer) {
                $customer->total_compras = (float) ($customer->total_compras ?? 0) + $total;
                $customer->numero_compras = (int) ($customer->numero_compras ?? 0) + 1;
                $customer->fecha_ultima_compra = now();
                $customer->save();
            }

            DB::commit();

            OrderNotificationService::sendPurchaseCompletedNotification($sale);

            $this->emit('pedido-creado', 'Pedido creado correctamente');
            $this->emit('hide-create-order-modal');
            $this->resetCreateOrderForm();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('despacho-error', 'Error al crear pedido: ' . $e->getMessage());
        }
    }

    public function getCartItemsCountProperty()
    {
        return count($this->cart);
    }

    public function getCartProductsCountProperty()
    {
        return array_sum(array_map(function ($item) {
            return (float) ($item['cantidad'] ?? 0);
        }, $this->cart));
    }

    public function getCartSubtotalProperty()
    {
        $subtotal = 0;
        foreach ($this->cart as $item) {
            $subtotal += ((float) $item['precio_final']) * ((float) $item['cantidad']);
        }

        return $subtotal;
    }

    public function getCartTaxesProperty()
    {
        $discount = max(0, (float) ($this->createDescuento ?? 0));
        $base = max(0, $this->cartSubtotal - $discount);

        return $base * 0.16;
    }

    public function getCartTotalProperty()
    {
        $discount = max(0, (float) ($this->createDescuento ?? 0));
        return max(0, $this->cartSubtotal - $discount) + $this->cartTaxes;
    }

    private function resetCreateOrderForm()
    {
        $this->createCustomerId = '';
        $this->createMetodoPago = 'efectivo';
        $this->createNotas = '';
        $this->createDescuento = 0;
        $this->productSearch = '';
        $this->cart = [];
    }

    public function openModal($saleId)
    {
        $this->selectedSaleId = $saleId;
        $this->loadSaleDetails();
        $this->emit('show-modal', 'open!');
    }

    public function closeModal()
    {
        $this->selectedSaleId = null;
        $this->saleDetails = [];
        $this->updatingDetailId = null;
    }

    private function loadSaleDetails()
    {
        $this->saleDetails = SaleDetail::where('sale_id', $this->selectedSaleId)
            ->with('product')
            ->get()
            ->toArray();
    }

    public function toggleProductDespacho($detailId)
    {
        $this->updatingDetailId = $detailId;

        try {
            $detail = SaleDetail::find($detailId);
            if (!$detail) {
                $this->emit('despacho-error', 'Detalle de venta no encontrado');
                return;
            }

            // Obtener el estado anterior de la venta
            $sale = Sale::find($detail->sale_id);
            $estadoAnterior = $sale->estado_envio;

            // Cambiar estado del producto
            $detail->estado_despacho = $detail->estado_despacho ? 0 : 1;
            $detail->save();

            // Verificar si todos los productos están despachados
            $totalProductos = $sale->details()->count();
            $productosDespachados = $sale->details()->where('estado_despacho', 1)->count();

            // Actualizar estado de la venta
            if ($productosDespachados == 0) {
                $sale->estado_envio = 'Pendiente';
            } elseif ($productosDespachados == $totalProductos) {
                $sale->estado_envio = 'Listo_para_enviar';
            } else {
                $sale->estado_envio = 'Procesando';
            }

            $sale->save();

            // Enviar notificación si el estado cambió y es notificable
            if (OrderNotificationService::shouldSendNotification($estadoAnterior, $sale->estado_envio)) {
                $emailSent = OrderNotificationService::sendStatusNotification($sale);

                if ($emailSent) {
                    $this->emit('despacho-updated', 'Estado actualizado y cliente notificado por email');
                } else {
                    $this->emit('despacho-updated', 'Estado actualizado (email no enviado - verificar datos del cliente)');
                }
            } else {
                $this->emit('despacho-updated', 'Estado actualizado correctamente');
            }

            // Recargar detalles
            $this->loadSaleDetails();

        } catch (\Exception $e) {
            $this->emit('despacho-error', 'Error: ' . $e->getMessage());
        } finally {
            $this->updatingDetailId = null;
        }
    }

    public function enviarPedido()
    {
        try {
            $sale = Sale::find($this->selectedSaleId);

            if (!$sale) {
                $this->emit('despacho-error', 'Venta no encontrada');
                return;
            }

            if ($sale->estado_envio !== 'Listo_para_enviar') {
                $this->emit('despacho-error', 'La venta no está lista para enviar');
                return;
            }

            // Verificar que todos los productos estén despachados
            $todosDespachados = $sale->details()->where('estado_despacho', 0)->count() == 0;

            if (!$todosDespachados) {
                $this->emit('despacho-error', 'No todos los productos están despachados');
                return;
            }

            // Cambiar estado a Enviado
            $sale->estado_envio = 'Enviado';
            $sale->save();

            // Enviar notificación de envío
            $emailSent = OrderNotificationService::sendStatusNotification($sale);

            $this->closeModal();

            if ($emailSent) {
                $this->emit('pedido-enviado', 'Pedido enviado exitosamente y cliente notificado por email');
            } else {
                $this->emit('pedido-enviado', 'Pedido enviado exitosamente (email no enviado - verificar datos del cliente)');
            }

        } catch (\Exception $e) {
            $this->emit('despacho-error', 'Error al enviar pedido: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Sale::with(['customer', 'details'])
            ->whereIn('estado_envio', ['Pendiente', 'Procesando', 'Listo_para_enviar'])
            ->orderBy('fecha_venta', 'asc'); // Más viejas primero

        // Compatibilidad con búsqueda general
        if ($this->search) {
            $query->where(function($q) {
                $q->where('folio', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function($subQ) {
                      $subQ->where('nombre', 'like', '%' . $this->search . '%')
                           ->orWhere('apellido', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filtroFolio) {
            $query->where('folio', 'like', '%' . $this->filtroFolio . '%');
        }

        if ($this->filtroCliente) {
            $query->whereHas('customer', function ($subQ) {
                $subQ->where('nombre', 'like', '%' . $this->filtroCliente . '%')
                    ->orWhere('apellido', 'like', '%' . $this->filtroCliente . '%');
            });
        }

        if ($this->filtroEstado) {
            $query->where('estado_envio', $this->filtroEstado);
        }

        $ventas = $query->paginate(15);

        // Calcular urgencias (más de 3 horas)
        $ventasUrgentes = [];
        foreach ($ventas as $venta) {
            $horasTranscurridas = Carbon::parse($venta->fecha_venta)->diffInHours(Carbon::now());
            if ($horasTranscurridas > 3) {
                $ventasUrgentes[] = $venta->id;
            }
        }

        $customers = Customers::orderBy('nombre')
            ->limit(300)
            ->get(['id', 'nombre', 'apellido']);

        $products = Product::where('activo', true)
            ->where(function ($query) {
                if (trim($this->productSearch) !== '') {
                    $query->where('codigo', 'like', '%' . $this->productSearch . '%')
                        ->orWhere('nombre', 'like', '%' . $this->productSearch . '%');
                }
            })
            ->orderBy('nombre')
            ->limit(25)
            ->get(['id', 'codigo', 'nombre', 'precio', 'precio_oferta', 'en_oferta', 'stock', 'unidad_venta']);

        return view('livewire.despachos.despachos-controller', [
            'ventas' => $ventas,
            'ventasUrgentes' => $ventasUrgentes,
            'customers' => $customers,
            'products' => $products,
        ])->extends('layouts.theme.app')
            ->section('content');
    }
}

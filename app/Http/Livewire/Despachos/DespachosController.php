<?php

namespace App\Http\Livewire\Despachos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use App\Services\OrderNotificationService;

class DespachosController extends Component
{
    use WithPagination;

    public $pageTitle, $componentName;
    public $selectedSaleId = null;
    public $saleDetails = [];
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refreshDespachos' => '$refresh',
        'closeModal' => 'closeModal'
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
            ->where('estado_envio', 'Pendiente')
            ->orWhere('estado_envio', 'Procesando')
            ->orWhere('estado_envio', 'Listo_para_enviar')
            ->orderBy('fecha_venta', 'asc'); // Más viejas primero

        // Filtro por búsqueda (folio o nombre de cliente)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('folio', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function($subQ) {
                      $subQ->where('nombre', 'like', '%' . $this->search . '%')
                           ->orWhere('apellido', 'like', '%' . $this->search . '%');
                  });
            });
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

        return view('livewire.despachos.despachos-controller', [
            'ventas' => $ventas,
            'ventasUrgentes' => $ventasUrgentes
        ])->extends('layouts.theme.app')
            ->section('content');
    }
}

<?php

namespace App\Http\Livewire\Ventas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Customers;
use App\Models\Product;

class VentasController extends Component
{
    use WithPagination;

    public $search = '';
    public $filtroEstatus = '';
    public $filtroMetodoPago = '';
    public $fechaInicio = '';
    public $fechaFin = '';
    public $selectedSaleId = null;
    public $componentName = 'Ventas';

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'cancelSale' => 'cancelSale'
    ];

    public function mount()
    {
        // Establecer fechas por defecto (último mes)
        $this->fechaFin = date('Y-m-d');
        $this->fechaInicio = date('Y-m-d', strtotime('-30 days'));
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstatus()
    {
        $this->resetPage();
    }

    public function updatingFiltroMetodoPago()
    {
        $this->resetPage();
    }

    public function updatingFechaInicio()
    {
        $this->resetPage();
    }

    public function updatingFechaFin()
    {
        $this->resetPage();
    }

    public function viewDetail($saleId)
    {
        $this->selectedSaleId = $saleId;
        $this->emit('show-detail-modal');
    }

    public function closeDetail()
    {
        $this->selectedSaleId = null;
    }

    public function cancelSale($saleId)
    {
        try {
            $sale = Sale::with('details')->find($saleId);

            if (!$sale) {
                $this->emit('sale-error', 'Venta no encontrada');
                return;
            }

            if ($sale->estatus == 'cancelada') {
                $this->emit('sale-error', 'La venta ya está cancelada');
                return;
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
            if ($customer) {
                $customer->total_compras -= $sale->total;
                $customer->numero_compras -= 1;
                $customer->save();
            }

            // Cambiar estatus
            $sale->estatus = 'cancelada';
            $sale->save();

            $this->emit('sale-cancelled', 'Venta cancelada exitosamente');
        } catch (\Exception $e) {
            $this->emit('sale-error', 'Error al cancelar la venta: ' . $e->getMessage());
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filtroEstatus = '';
        $this->filtroMetodoPago = '';
        $this->fechaInicio = date('Y-m-d', strtotime('-30 days'));
        $this->fechaFin = date('Y-m-d');
        $this->resetPage();
    }

    public function render()
    {
        $query = Sale::with(['customer', 'details'])->orderBy('fecha_venta', 'desc');
        $query = $this->applyFilters($query);

        $ventas = $query->paginate(15);

        // Calcular totales
        $totalesQuery = Sale::query();
        $totalesQuery = $this->applyFilters($totalesQuery);

        $totales = [
            'total_ventas' => (clone $totalesQuery)
                ->where('estatus', '!=', 'cancelada')
                ->sum('total'),
            'numero_ventas' => (clone $totalesQuery)
                ->where('estatus', '!=', 'cancelada')
                ->count(),
            'ventas_hoy' => (clone $totalesQuery)
                ->whereDate('fecha_venta', today())
                ->where('estatus', '!=', 'cancelada')
                ->sum('total'),
        ];

        return view('livewire.ventas.ventas-controller', [
            'ventas' => $ventas,
            'totales' => $totales
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    private function applyFilters($query)
    {
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('folio', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($subQ) {
                        $subQ->where('nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('apellido', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->filtroEstatus) {
            $query->where('estatus', $this->filtroEstatus);
        }

        if ($this->filtroMetodoPago) {
            $query->where('metodo_pago', $this->filtroMetodoPago);
        }

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('fecha_venta', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59']);
        } elseif ($this->fechaInicio) {
            $query->where('fecha_venta', '>=', $this->fechaInicio . ' 00:00:00');
        } elseif ($this->fechaFin) {
            $query->where('fecha_venta', '<=', $this->fechaFin . ' 23:59:59');
        }

        return $query;
    }
}

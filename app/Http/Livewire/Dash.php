<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Livewire\Component;

class Dash extends Component
{
    public $year;
    public $salesByMonthData = [];
    public $top5Labels = [];
    public $top5Data = [];
    public $weekSalesData = [];

    public function mount()
    {
        $this->year = Carbon::now()->year;
        $this->loadSalesByMonth();
        $this->loadTopProducts();
        $this->loadWeekSales();
    }

    private function loadSalesByMonth()
    {
        $monthlyTotals = Sale::query()
            ->selectRaw('MONTH(fecha_venta) as month, COALESCE(SUM(total),0) as total')
            ->whereYear('fecha_venta', $this->year)
            ->where('estatus', 'completada')
            ->groupByRaw('MONTH(fecha_venta)')
            ->pluck('total', 'month')
            ->toArray();

        $this->salesByMonthData = [];
        for ($month = 1; $month <= 12; $month++) {
            $this->salesByMonthData[] = (float) ($monthlyTotals[$month] ?? 0);
        }
    }

    private function loadTopProducts()
    {
        $topProducts = SaleDetail::query()
            ->selectRaw('COALESCE(producto_nombre, "Producto") as product, COALESCE(SUM(cantidad),0) as total')
            ->whereHas('sale', function ($q) {
                $q->whereYear('fecha_venta', $this->year)
                    ->where('estatus', 'completada');
            })
            ->groupBy('producto_nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $this->top5Labels = $topProducts->pluck('product')->map(function ($item) {
            return (string) $item;
        })->toArray();

        $this->top5Data = $topProducts->pluck('total')->map(function ($item) {
            return (float) $item;
        })->toArray();

        if (count($this->top5Data) === 0) {
            $this->top5Labels = ['Sin datos'];
            $this->top5Data = [0];
        }
    }

    private function loadWeekSales()
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $weekTotals = Sale::query()
            ->selectRaw('WEEKDAY(fecha_venta) as day_index, COALESCE(SUM(total),0) as total')
            ->whereBetween('fecha_venta', [$startOfWeek, $endOfWeek])
            ->where('estatus', 'completada')
            ->groupByRaw('WEEKDAY(fecha_venta)')
            ->pluck('total', 'day_index')
            ->toArray();

        $this->weekSalesData = [];
        for ($day = 0; $day <= 6; $day++) {
            $this->weekSalesData[] = (float) ($weekTotals[$day] ?? 0);
        }
    }

    public function render()
    {
        return view('livewire.dash.component', [
            'year' => $this->year,
            'salesByMonthData' => $this->salesByMonthData,
            'top5Labels' => $this->top5Labels,
            'top5Data' => $this->top5Data,
            'weekSalesData' => $this->weekSalesData,
        ])->extends('layouts.theme.app')
            ->section('content');
    }
}

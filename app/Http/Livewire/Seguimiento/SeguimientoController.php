<?php

namespace App\Http\Livewire\Seguimiento;

use App\Models\Customers;
use App\Models\SeguimientoClientesImagenes;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SeguimientoController extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $pageTitle, $componentName;
    private $pagination = 10;
    public $selected_id;
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Seguimiento';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function render()
    {
        $clientes =  Customers::paginate($this->pagination);

        return view('livewire.seguimiento.seguimiento-controller', ['clientes' => $clientes])->extends('layouts.theme.app')
            ->section('content');
    }
    public $clienteIdSeleccionado;
    public $imagenesSeguimientoCliente = [];

    public function verImagenesCliente($clienteId)
    {
        $this->emit('modal-videos');
        $this->clienteIdSeleccionado = $clienteId;
        $this->imagenesSeguimientoCliente = SeguimientoClientesImagenes::where('customers_id', $clienteId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($img) {
                return [
                    'image' => 'data:image/png;base64,' . $img->image,
                    'peso' => $img->peso,
                    'comentarios' => $img->comentarios,
                    'created_at' => $img->created_at->format('d M Y'),
                    'mes' => $img->created_at->format('n'),
                ];
            })->toArray();
    }
}

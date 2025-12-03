<?php

namespace App\Http\Livewire\GruposMusculares;

use Livewire\Component;
use App\Models\GruposMusculares;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GruposMuscularesController extends Component
{
    use WithPagination;
    use WithFileUploads;
    private $pagination = 3;
    public $imagen;
    public $nombre;
    public $descripcion;
    public $pageTitle, $componentName;
    public $selected_id;
    public function render()
    {
        $grupos = GruposMusculares::paginate($this->pagination);
        return view('livewire.grupos-musculares.grupos-musculares-controller', [
            'grupos' => $grupos
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Grupos Musculares';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function resetUI()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->imagen = '';


        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
    }

    public function edit(GruposMusculares $user)
    {
        $this->selected_id = $user->id;
        $this->nombre = $user->nombre;
        $this->descripcion = $user->descripcion;
        $this->imagen = $user->imagen;

        $this->emit('show-modal', 'open!');
    }
    public function Store()
    {
        $rules = [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:3',
            'imagen' => 'required',
        ];

        $this->validate($rules);

        try {


            $user = GruposMusculares::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'imagen' => $this->imagen,
            ]);

            $this->resetUI();
            $this->emit('user-added', 'Usuario Registrado');
        } catch (\Exception $e) {
            // Manejar la excepción (puedes personalizar este mensaje o realizar otras acciones)
            $this->emit('error', 'Ocurrió un error al registrar el usuario: ' . $e->getMessage());
        }
    }


    public function Update()
{
    $rules = [
        'nombre' => 'required|min:3',
        'descripcion' => 'required|min:3',
        'imagen' => 'required|min:3',  // Posiblemente cambiar la validación aquí si se maneja como base64 en algunos casos.
    ];

    $this->validate($rules);

    $user = GruposMusculares::find($this->selected_id);
    $miniaturaBase64 = $this->imagen;


    $user->update([
        'nombre' => $this->nombre,
        'descripcion' => $this->descripcion,
        'imagen' =>  $this->imagen
    ]);

    $this->resetUI();
    $this->emit('user-updated', 'Usuario Actualizado');
}

    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'

    ];

    public function destroy(GruposMusculares $user)
    {



        $user->delete();
        $this->resetUI();
        $this->emit('user-deleted', 'Usuario Eliminado');
    }
}

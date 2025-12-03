<?php

namespace App\Http\Livewire\Tags;

use Livewire\Component;
use App\Models\Tag;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TagsController extends Component
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
        $tags = Tag::paginate($this->pagination);
        return view('livewire.tags.tags-controller', [
            'tags' => $tags
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Tags';
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

    public function edit(Tag $user)
    {
        $this->selected_id = $user->id;
        $this->nombre = $user->nombre;


        $this->emit('show-modal', 'open!');
    }
    public function Store()
    {
        $rules = [
            'nombre' => 'required|min:3',

        ];

        $this->validate($rules);

        try {


            $user = Tag::create([
                'nombre' => $this->nombre,

            ]);

            $this->resetUI();
            $this->emit('user-added', 'Usuario Registrado');
        } catch (\Exception $e) {
            dd($e);
            // Manejar la excepción (puedes personalizar este mensaje o realizar otras acciones)
            $this->emit('error', 'Ocurrió un error al registrar el usuario: ' . $e->getMessage());
        }
    }


    public function Update()
    {
        $rules = [
            'nombre' => 'required|min:3',


        ];



        $this->validate($rules);

        $user = Tag::find($this->selected_id);
        $user->update([
            'nombre' => $this->nombre,


        ]);

        $this->resetUI();
        $this->emit('user-updated', 'Usuario Actualizado');
    }
    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'

    ];

    public function destroy(Tag $user)
    {



        $user->delete();
        $this->resetUI();
        $this->emit('user-deleted', 'Usuario Eliminado');
    }
}

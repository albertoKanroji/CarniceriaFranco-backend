<?php

namespace App\Http\Livewire\Categorias;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CategoriasController extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $pageTitle, $componentName;
    private $pagination = 10;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $activo = 1;
    public $orden = 0;
    public $selected_id = 0;
    public $search;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Categorías';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $data = Category::when($this->search, function($query) {
            return $query->where('nombre', 'like', '%' . $this->search . '%');
        })->orderBy('orden', 'asc')->paginate($this->pagination);

        return view('livewire.categorias.categorias-controller', ['data' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function resetUI()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->imagen = null;
        $this->activo = 1;
        $this->orden = 0;
        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
    }

    public function edit(Category $category)
    {
        $this->selected_id = $category->id;
        $this->nombre = $category->nombre;
        $this->descripcion = $category->descripcion;
        $this->activo = $category->activo;
        $this->orden = $category->orden;
        $this->emit('show-modal', 'open!');
    }

    public function Store()
    {
        $rules = [
            'nombre' => 'required|min:3|unique:categories',
            'activo' => 'required|boolean',
            'orden' => 'required|integer|min:0',
        ];

        $messages = [
            'nombre.required' => 'Ingresa el nombre de la categoría',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'nombre.unique' => 'Esta categoría ya existe',
            'activo.required' => 'Selecciona el estado',
            'orden.required' => 'Ingresa el orden',
            'orden.integer' => 'El orden debe ser un número',
        ];

        $this->validate($rules, $messages);

        $imageName = null;
        if ($this->imagen) {
            $imageName = uniqid() . '_.' . $this->imagen->extension();
            $this->imagen->storeAs('public/categories', $imageName);
        }

        Category::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'imagen' => $imageName,
            'activo' => $this->activo,
            'orden' => $this->orden,
        ]);

        $this->resetUI();
        $this->emit('category-added', 'Categoría Registrada');
    }

    public function Update()
    {
        $rules = [
            'nombre' => 'required|min:3|unique:categories,nombre,' . $this->selected_id,
            'activo' => 'required|boolean',
            'orden' => 'required|integer|min:0',
        ];

        $messages = [
            'nombre.required' => 'Ingresa el nombre de la categoría',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'nombre.unique' => 'Esta categoría ya existe',
        ];

        $this->validate($rules, $messages);

        try {
            $category = Category::find($this->selected_id);

            $imageName = $category->imagen;
            if ($this->imagen) {
                $imageName = uniqid() . '_.' . $this->imagen->extension();
                $this->imagen->storeAs('public/categories', $imageName);

                if ($category->imagen) {
                    $oldImagePath = storage_path('app/public/categories/' . $category->imagen);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            $category->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'imagen' => $imageName,
                'activo' => $this->activo,
                'orden' => $this->orden,
            ]);

            $this->resetUI();
            $this->emit('category-updated', 'Categoría Actualizada');
        } catch (\Exception $e) {
            $this->emit('category-error', 'Error: ' . $e->getMessage());
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'
    ];

    public function destroy(Category $category)
    {
        try {
            if ($category->products()->count() > 0) {
                $this->emit('category-error', 'No se puede eliminar. Tiene productos asociados.');
                return;
            }

            if ($category->imagen) {
                $imagePath = storage_path('app/public/categories/' . $category->imagen);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $category->delete();
            $this->resetUI();
            $this->emit('category-deleted', 'Categoría eliminada con éxito');
        } catch (\Exception $e) {
            $this->emit('category-error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}

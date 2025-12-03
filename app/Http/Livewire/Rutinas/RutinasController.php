<?php

namespace App\Http\Livewire\Rutinas;

use App\Models\GruposMuscularesVideos;
use Livewire\Component;
use App\Models\Rutinas;
use Exception;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class RutinasController extends Component
{
    use WithPagination;
    use WithFileUploads;
    private $pagination = 3;
    public $nombre;
    public $imagen;
    public $descripcion;
    public $tipo;
    public $max_puntaje;
    public $min_puntaje;
    public $estado;

    public $pageTitle, $componentName;
    public $selected_id;
    public function render()
    {
        $rutinas = Rutinas::where('estado', 'publica')->paginate($this->pagination);

        return view('livewire.rutinas.rutinas-controller', [
            'rutinas' => $rutinas
        ])->extends('layouts.theme.app')
            ->section('content');
    }
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Rutinas Publicas';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function resetUI()
    {
        $this->nombre = '';
        $this->imagen = '';
        $this->descripcion = '';
        $this->tipo = '';
        $this->max_puntaje = '';
        $this->min_puntaje = '';
        $this->estado = '';

        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
    }

    public function edit(Rutinas $user)
    {
        $this->selected_id = $user->id;
        $this->nombre = $user->nombre;
        $this->imagen = $user->imagen;
        $this->descripcion = $user->descripcion;
        $this->tipo = $user->tipo;
        $this->max_puntaje = $user->max_puntaje;
        $this->min_puntaje = $user->min_puntaje;
        $this->estado = $user->estado;

        $this->emit('show-modal', 'open!');
    }

    public function Store()
    {
        $rules = [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:3',

            'tipo' => 'required|min:3',
            // 'max_puntaje' => 'required|numeric',
            // 'min_puntaje' => 'required|numeric',
            //'estado' => 'required|in:publica,perzonalizada',
        ];

        $this->validate($rules);

        try {




            $user = Rutinas::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'imagen' => $this->imagen,
                'tipo' => $this->tipo,
                'max_puntaje' => $this->max_puntaje,
                'min_puntaje' => $this->min_puntaje,
                'estado' =>'publica',
            ]);

            $this->resetUI();
            $this->emit('video-updated', 'Rutina Actualizada');
        } catch (Exception $e) {
            // Manejar la excepción (puedes personalizar este mensaje o realizar otras acciones)
            $this->emit('error', 'Ocurrió un error al registrar el usuario: ' . $e->getMessage());
        }
    }

    public function Update()
    {
        $rules = [
            'nombre' => 'required|min:3',
            'descripcion' => 'required|min:3',

            'tipo' => 'required|min:3',
            // 'max_puntaje' => 'required|numeric',
            // 'min_puntaje' => 'required|numeric',
          //  'estado' => 'required|in:publica,perzonalizada',
        ];

        $this->validate($rules);
        try {

            $user = Rutinas::find($this->selected_id);
            $user->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'imagen' => $this->imagen,
                'tipo' => $this->tipo,
                'max_puntaje' => $this->max_puntaje,
                'min_puntaje' => $this->min_puntaje,
               // 'estado' => $this->estado,
            ]);

            $this->resetUI();
            $this->emit('user-updated', 'Usuario Actualizado');
            $this->emit('video-updated', 'Rutina Actualizada');
        } catch (\Exception $e) {
            dd($e);
            // Manejar la excepción (puedes personalizar este mensaje o realizar otras acciones)
            $this->emit('error', 'Ocurrió un error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'

    ];

    public function destroy(Rutinas $user)
    {

   $user->videos()->detach(); // Si usas belongsToMany
        $user->customers()->detach();

        // También puedes borrar ejercicios relacionados si son registros dependientes
        // $user->ejercicios()->delete();

        $user->delete();

        $user->delete();
        $this->resetUI();
        $this->emit('user-deleted', 'Usuario Eliminado');
    }
    public $search = '';
    public $videos = [];
    public $rutinaId; // ID de la rutina seleccionada
    public $diaSeleccionado = 'Lunes';

    public function ejercicios($id)
    {
        $this->rutinaId = $id;
        $this->emit('modal-videos');
        $this->videos = GruposMuscularesVideos::with(['tags', 'equipos', 'grupoMuscular'])->get();
        $this->cargarVideosAgregados();
    }
    public $videosAgregados = [];
    public function cargarVideosAgregados()
    {
        $rutina = Rutinas::find($this->rutinaId);

        if ($rutina) {
            $this->videosAgregados = $rutina->videos()
            ->select('videos_gm.id as id', 'rutinas_ejercicios.dia')
                ->get()
                ->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'dia' => $video->dia
                    ];
                })->toArray();
        }
    }

    public function cargarVideos()
    {
        $this->videos = GruposMuscularesVideos::with(['tags', 'equipos', 'grupoMuscular'])
            ->where('nombre', 'like', '%' . $this->search . '%') // Filtro por búsqueda
            ->get();
    }
    public function updatedSearch()
    {
        $this->cargarVideos(); // Se actualiza la lista al escribir en el buscador
    }

    public function agregarEjercicio($videoId)
    {
        try {
            $rutina = Rutinas::find($this->rutinaId);

            if (!$rutina) {
                session()->flash('error', 'Rutina no encontrada.');
                return;
            }

            $video = GruposMuscularesVideos::find($videoId);

            if ($video) {
                // Agregar el ejercicio a la rutina con el día seleccionado en la tabla pivote
                $rutina->videos()->attach($videoId, ['dia' => $this->diaSeleccionado]);
                $this->cargarVideosAgregados();
                $this->emit('global-msg', 'Ejercicio agregado correctamente.');
            }
        } catch (
            Exception $th
        ) {
            $this->emit('global-msg', 'Ocurrió un error al agregar el ejercicio a la rutina. Intente nuevamente. ' . $th->getMessage());
            //throw $th;
        }
    }
    public function eliminarEjercicio($videoId)
    {
        try {
            $rutina = Rutinas::find($this->rutinaId);

            if (!$rutina) {
                session()->flash('error', 'Rutina no encontrada.');
                return;
            }

            if (!collect($this->videosAgregados)->contains('id', $videoId)) {
                $this->emit('global-msg', 'Este ejercicio no está en la rutina.');
                return;
            }

            $rutina->videos()->detach($videoId); // Elimina el ejercicio de la tabla pivote

            $this->cargarVideosAgregados(); // Recargar lista después de eliminar
            $this->emit('global-msg', 'Ejercicio eliminado correctamente.');
        } catch (Exception $th) {
            $this->emit('global-msg', 'Error al eliminar el ejercicio: ' . $th->getMessage());
        }
    }
}

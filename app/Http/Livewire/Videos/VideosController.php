<?php

namespace App\Http\Livewire\Videos;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\GruposMuscularesVideos;
use App\Models\Tag;
use App\Models\Equipo;
use App\Models\GruposMusculares;

class VideosController extends Component
{
    public $nombre;
    public $miniatura;
    public $descripcion;
    public $gm_id;
    public $video_url;
    public $selected_id;
    public $search;
    public $tags;
    public $lesion;
    public $equipos;
    use WithPagination;
    use WithFileUploads;
    public $page = 1;
    public $pageTitle, $componentName;
    private $pagination = 3;
    protected $rules = [
        'nombre' => 'required|min:3',
        'miniatura' => 'nullable',
        'descripcion' => 'required|min:3',
        'gm_id' => 'required|exists:grupos_musculares,id',
        'video_url' => 'required|url',
        'tags' => 'required',

        'equipos' => 'required',

    ];

    protected $messages = [
        'nombre.required' => 'Ingresa el nombre',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres',


        'descripcion.required' => 'Ingresa la descripción',
        'descripcion.min' => 'La descripción debe tener al menos 3 caracteres',
        'gm_id.required' => 'Selecciona un grupo muscular',
        'gm_id.exists' => 'Grupo muscular no válido',
        'video_url.required' => 'Ingresa la URL del video',
        'video_url.url' => 'Ingresa una URL válida',
        'tags.required' => 'Las etiquetas deben ser un array',
        'equipos.required' => 'Los equipos deben ser un array',
    ];
    public function mount()
    {
        $this->nombre = '';
        $this->miniatura = '';
        $this->descripcion = '';
        $this->gm_id = null;
        $this->video_url = '';
        $this->tags;
        $this->equipos;
        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
        $this->pageTitle = 'Listado';
        $this->componentName = 'Videos';
    }
    public function resetUI()
    {
        $this->nombre = '';
        $this->miniatura = '';
        $this->descripcion = '';
        $this->gm_id = null;
        $this->video_url = '';
        $this->tags = []; // Asegúrate de resetear a un array vacío
        $this->equipos = []; // Asegúrate de resetear a un array vacío
        $this->lesion = '';
        $this->selected_id = 0;
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $gruposMusculares = GruposMusculares::all();
        $equipo = Equipo::all();
        $tags = Tag::all();
        //dd($tags);
        $customers = GruposMuscularesVideos::with(['tags', 'equipos', 'grupoMuscular'])->paginate(10);
        return view('livewire.videos.videos-controller', [
            'data' => $customers,
            'gruposMusculares' => $gruposMusculares,
            'etiqueta' =>  $tags,
            'eq' => $equipo

        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
    public function Store()
    {
        //$this->validate();




        // Guardar los datos
        $data = [
            'nombre' => $this->nombre,
            'miniatura' =>  $this->miniatura,
            'descripcion' => $this->descripcion,
            'gm_id' => $this->gm_id,
            'video_url' => $this->video_url
        ];
        $video = GruposMuscularesVideos::create($data);

        if (!empty($this->tags)) {
            $video->tags()->sync($this->tags);
        }

        if (!empty($this->equipos)) {
            $video->equipos()->sync($this->equipos);
        }

        $this->resetUI();
        $this->emit('video-added', 'Video Registrado');
    }
    public function edit(GruposMuscularesVideos $video)
    {
        $this->selected_id = $video->id;
        $this->nombre = $video->nombre;
        $this->miniatura = $video->miniatura;
        $this->descripcion = $video->descripcion;
        $this->gm_id = $video->gm_id;
        $this->video_url = $video->video_url;
        $this->lesion = $video->lesion;
        $this->tags = $video->tags->pluck('id')->toArray(); // Cargar IDs de tags
        $this->equipos = $video->equipos->pluck('id')->toArray();

        $this->emit('show-modal', 'open!');
    }
    public function Update()
    {
        // Validación
        $this->validate();

        try {
            $video = GruposMuscularesVideos::findOrFail($this->selected_id);

            // Actualizar los datos básicos
            $video->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'gm_id' => $this->gm_id,
                'video_url' => $this->video_url,
                'lesion' => $this->lesion,
                'miniatura' =>  $this->miniatura,

            ]);

            $this->tags = $this->tags ?: $video->tags->pluck('id')->toArray();
            $this->equipos = $this->equipos ?: $video->equipos->pluck('id')->toArray();

            // Guardar los cambios
            $video->save();
            //dd($this->tags);
            // Sincronizar etiquetas y equipos
            $video->tags()->sync($this->tags);
            $video->equipos()->sync($this->equipos);
            $this->resetUI();
            $this->emit('video-updated', 'Video Actualizado');
        } catch (\Exception $e) {
            // Emitir mensaje de error
            dd($e);
            $this->emit('video-updated', 'Error al actualizar el video');
        }
    }


    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'
    ];

    public function destroy(GruposMuscularesVideos $video)
    {


        $video->tags()->detach();
        $video->equipos()->detach();
        $video->delete();

        $this->resetUI();
        $this->emit('video-deleted', 'Video Eliminado');
    }
}

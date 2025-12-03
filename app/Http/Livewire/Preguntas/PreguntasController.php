<?php

namespace App\Http\Livewire\Preguntas;

use App\Models\Pregunta;
use App\Models\RespuestaOpcion;
use Exception;
use Livewire\Component;

class PreguntasController extends Component
{
    public $preguntasConOpciones = [];
    public $editingValues = [];
    public $nuevaPregunta = '';
public $nuevasOpciones = [['opcion' => '', 'valor' => '']];

public function agregarOpcion()
{
    $this->nuevasOpciones[] = ['opcion' => '', 'valor' => ''];
}

public function eliminarOpcion($index)
{
    unset($this->nuevasOpciones[$index]);
    $this->nuevasOpciones = array_values($this->nuevasOpciones);
}

public function guardarPregunta()
{
    $this->validate([
        'nuevaPregunta' => 'required|string|min:3',
        'nuevasOpciones.*.opcion' => 'required|string',
        'nuevasOpciones.*.valor' => 'required|numeric|min:0'
    ]);

    try {
        $pregunta = Pregunta::create(['pregunta' => $this->nuevaPregunta]);

        foreach ($this->nuevasOpciones as $opcionData) {
            RespuestaOpcion::create([
                'preguntas_id' => $pregunta->id,
                'opcion' => $opcionData['opcion'],
                'valor' => $opcionData['valor']
            ]);
        }

        // Reset campos
        $this->nuevaPregunta = '';
        $this->nuevasOpciones = [['opcion' => '', 'valor' => '']];

        $this->emit('global-msg', 'Pregunta agregada con éxito');
        $this->loadPreguntas();
    } catch (Exception $e) {
        $this->emit('global-msg', 'Error al agregar pregunta: ' . $e->getMessage());
    }
}
    public function mount()
    {
        $this->loadPreguntas();
    }

    public function loadPreguntas()
    {
        $this->preguntasConOpciones = Pregunta::with('respuestasOpciones')->get();

        // Cargar valores editables por defecto
        foreach ($this->preguntasConOpciones as $pregunta) {
            foreach ($pregunta->respuestasOpciones as $opcion) {
                $this->editingValues[$opcion->id] = [
                    'opcion' => $opcion->opcion,
                    'valor' => $opcion->valor,
                ];
            }
        }
    }


    public function updatedEditingValues()
    {
        // Este método se dispara automáticamente cuando se edita algún campo
    }

  public function updateOpcion($opcionId)
{
    try {
        dd($this->editingValues[$opcionId]);
        if (isset($this->editingValues[$opcionId])) {
            $data = $this->editingValues[$opcionId];
            $opcion = RespuestaOpcion::find($opcionId);

            if ($opcion && ($opcion->opcion !== $data['opcion'] || $opcion->valor != $data['valor'])) {
                $opcion->update([
                    'opcion' => $data['opcion'],
                    'valor' => $data['valor'],
                ]);
                $this->emit('global-msg', 'Opción actualizada correctamente.');
                $this->loadPreguntas();
            }
        }
    } catch (Exception $e) {
        $this->emit('global-msg', 'Ocurrió un error al actualizar: ' . $e->getMessage());
    }
}


    public function deleteOpcion($opcionId)
    {
       try {
        $opcion = RespuestaOpcion::withCount('respuestas')->find($opcionId);
        if ($opcion) {
            if ($opcion->respuestas_count > 0) {
                $this->emit('global-msg','No se puede eliminar esta opción porque ya fue respondida por algún usuario.');
                return;
            }
            $opcion->delete();
            $this->emit('global-msg','Opción eliminada.');
            $this->loadPreguntas();
        }
       } catch (Exception $e) {
        dd($e);
        //throw $th;
        $this->emit('global-msg','Ocurrio un error: '.$e);
       }
    }

    public function render()
    {
        return view('livewire.preguntas.preguntas-controller')
            ->extends('layouts.theme.app')
            ->section('content');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\RespuestaOpcion;
use App\Models\Rutinas;
use Illuminate\Http\Request;
use App\Models\Pregunta;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Respuesta;

class PreguntasControllerAPI extends Controller
{
    //
    public function index()
    {
        try {
            $preguntas = Pregunta::with('respuestasOpciones')->get();

            return response()->json($preguntas, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener las preguntas: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al obtener las preguntas',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function guardarRespuestas(Request $request)
    {
        try {
            $userId = $request->input('userId');
            $respuestasData = $request->input('respuestas');

            // Buscar cliente
            $cliente = Customers::find($userId);
            if (!$cliente) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }

            $puntaje = 0;

            // Guardar respuestas
            foreach ($respuestasData as $respuestaData) {
                $preguntaId = $respuestaData['preguntaId'];
                $valor = $respuestaData['respuestaValor']; // Este es el valor, no el ID

                $respuestaOpcion = RespuestaOpcion::where('preguntas_id', $preguntaId)
                    ->where('valor', $valor)
                    ->first();

                if (!$respuestaOpcion) {
                    return response()->json([
                        'message' => "No se encontró una opción de respuesta con valor '{$valor}' para la pregunta ID '{$preguntaId}'."
                    ], 400);
                }

                // Crear la respuesta
                Respuesta::create([
                    'preguntas_id' => $preguntaId,
                    'respuestas_opciones_id' => $respuestaOpcion->id,
                    'customers_id' => $userId,
                ]);

                // Acumular puntaje
                $puntaje += intval($valor);
            }

            // Añadir el puntaje base según sexo
            $genero = $cliente->sexo;
            

            // Asignar nivel
           $nivel = null;
            if ($genero === 'Hombre' || $genero === 'Masculino') {
                if ($puntaje >= 30 && $puntaje <= 45) {
                    $nivel = 'Principiante';
                } elseif ($puntaje >= 46 && $puntaje <= 61) {
                    $nivel = 'Intermedio';
                } elseif ($puntaje >= 61 && $puntaje <= 77) {
                    $nivel = 'Avanzado';
                }
            }


            Log::info("Puntaje total: $puntaje");
            Log::info("Nivel asignado: $nivel");

            $cliente->nivel = $nivel;
            $cliente->save();

            // Asignar rutina
              $rutina = Rutinas::where('sexo', $genero)
                ->where('tipo', $nivel)
                ->where('puntaje', $puntaje)
                ->first();

            if ($rutina) {
                $cliente->rutinas()->syncWithoutDetaching([$rutina->id]);
                Log::info('Rutina asignada: ' . $rutina->nombre);
            } else {
                Log::warning("No se encontró rutina para nivel: $nivel y género: $genero");
            }

            return response()->json([
                'message' => 'Respuestas guardadas exitosamente',
                'puntaje' => $puntaje,
                'nivel_asignado' => $nivel,
                'rutina_asignada' => $rutina ? $rutina->nombre : 'No se encontró rutina'
            ], 200);
        } catch (Exception $e) {
            Log::error('Error al guardar respuestas: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al guardar respuestas: ' . $e->getMessage()
            ], 500);
        }
    }
}

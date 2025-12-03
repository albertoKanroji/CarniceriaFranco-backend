<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use App\Models\Rutinas;
use Illuminate\Support\Facades\Log;

class RutinasControllerAPI extends Controller
{
    //
    public function index()
    {
        // Obtener todas las rutinas
        $rutinas = Rutinas::where('estado', 'publica')->get();

        // Retornar las rutinas como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $rutinas
        ]);
    }
    public function show($id)
    {
        // Buscar la rutina por su ID
        $rutina = Rutinas::find($id);

        // Verificar si la rutina existe
        if (!$rutina) {
            return response()->json([
                'success' => false,
                'message' => 'Rutina no encontrada'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Retornar la rutina como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $rutina
        ]);
    }
    public function showEjercicios($id)
    {
        // Buscar la rutina por su ID
        $rutina = Rutinas::find($id);

        // Verificar si la rutina existe
        if (!$rutina) {
            return response()->json([
                'success' => false,
                'message' => 'Rutina no encontrada'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Obtener los ejercicios junto con el campo 'dia' desde la tabla pivote
        $ejercicios = $rutina->videos()->withPivot('dia')->get();

        // Ocultar el campo 'miniatura' en cada modelo
        $ejercicios->each(function ($video) {
            $video->makeHidden('miniatura');
        });

        // Agrupar por día desde el campo pivote
        $agrupados = $ejercicios->groupBy('pivot.dia');

        // Definir el orden de los días
        $diasOrdenados = [
            'Lunes' => 1,
            'Martes' => 2,
            'Miércoles' => 3,
            'Jueves' => 4,
            'Viernes' => 5,
            'Sábado' => 6,
        ];

        // Ordenar los ejercicios por los días de la semana
        // Ordenar los días
        $ejerciciosOrdenados = collect($agrupados)->sortBy(function ($items, $dia) use ($diasOrdenados) {
            return $diasOrdenados[$dia] ?? 7;
        })->toArray(); // Convertir la colección ordenada a array

        // Retornar los ejercicios agrupados y ordenados por día como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $ejerciciosOrdenados
        ]);
    }


    public function obtenerRutinasPersonalizadas($clienteId)
    {
        try {
            // Buscar al cliente por su ID
            $cliente = Customers::find($clienteId);

            if (!$cliente) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }

            // Obtener las rutinas personalizadas del cliente
            $rutinasPersonalizadas = $cliente->rutinas()->get();

            return response()->json([
                'message' => 'Rutinas personalizadas obtenidas correctamente',
                'data' => $rutinasPersonalizadas
            ], 200);
        } catch (\Exception $e) {
            // Registrar el error en el log de la aplicación
            Log::error('Error al obtener las rutinas personalizadas: ' . $e->getMessage());

            // Retornar una respuesta de error en formato JSON
            return response()->json(['message' => 'Error al obtener las rutinas personalizadas'], 500);
        }
    }
}

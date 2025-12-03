<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GruposMusculares;
use App\Models\GruposMuscularesVideos;
use App\Models\Equipo;

class GruposMuscularesControllerlAPI extends Controller
{
    public function index()
    {
        // Obtener todas las rutinas
        $rutinas = GruposMusculares::all();

        // Retornar las rutinas como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $rutinas
        ]);
    }
    public function show($id)
    {
        // Buscar la rutina por su ID
        $rutina = GruposMusculares::find($id);

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
    public function showVideos($id)
    {
        // Buscar la rutina por su ID
        $rutina = GruposMusculares::find($id);

        // Verificar si la rutina existe
        if (!$rutina) {
            return response()->json([
                'success' => false,
                'message' => 'Rutina no encontrada'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Obtener todos los ejercicios asociados a la rutina
        $ejercicios = $rutina->videos;

        // Retornar los ejercicios como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $ejercicios
        ]);
    }
    public function getVideos(Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $page = $request->input('page', 1);

        $gruposMusculares = GruposMusculares::with('videos')->get();
        $allVideos = $gruposMusculares->flatMap(function ($grupoMuscular) {
            return $grupoMuscular->videos;
        });

        $paginated = $allVideos->forPage($page, $perPage)->values();

        $filtered = $paginated->map(function ($video) {
            return [
                'id' => $video->id,
                'nombre' => $video->nombre,
                'miniatura' => $video->miniatura,
                'gm_id' => $video->gm_id,
            ];
        });

        $total = $allVideos->count();

        return response()->json([
            'success' => true,
            'data' => $filtered,
            'meta' => [
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage)
            ]
        ]);
    }


    public function getVideosTag($id)
    {
        // Buscar la rutina por su ID
        $rutina = GruposMuscularesVideos::find($id);

        // Verificar si la rutina existe
        if (!$rutina) {
            return response()->json([
                'success' => false,
                'message' => 'Rutina no encontrada'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Obtener todos los ejercicios asociados a la rutina
        $ejercicios = $rutina->tags;

        // Retornar los ejercicios como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $ejercicios
        ]);
    }
    public function getVideosEquipo($id)
    {
        // Buscar la rutina por su ID
        $rutina = GruposMuscularesVideos::find($id);

        // Verificar si la rutina existe
        if (!$rutina) {
            return response()->json([
                'success' => false,
                'message' => 'Rutina no encontrada'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Obtener todos los ejercicios asociados a la rutina
        $ejercicios = $rutina->equipos;

        // Retornar los ejercicios como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $ejercicios
        ]);
    }
    public function showVideoDetail($id)
    {
        // Buscar la rutina por su ID
        $rutina = GruposMuscularesVideos::find($id);

        // Verificar si la rutina existe
        if (!$rutina) {
            return response()->json([
                'success' => false,
                'message' => 'video no encontrada'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Retornar la rutina como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $rutina
        ]);
    }
    public function getTags($id)
    {
        // Buscar el grupo muscular por su ID
        $grupoMuscular = GruposMusculares::find($id);

        // Verificar si el grupo muscular existe
        if (!$grupoMuscular) {
            return response()->json([
                'success' => false,
                'message' => 'Grupo muscular no encontrado'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Obtener los tags asociados al grupo muscular
        $tags = $grupoMuscular->tags;

        // Retornar los tags como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }
    public function getEquipo($id)
    {
        // Buscar el grupo muscular por su ID
        $grupoMuscular = GruposMusculares::find($id);

        // Verificar si el grupo muscular existe
        if (!$grupoMuscular) {
            return response()->json([
                'success' => false,
                'message' => 'Grupo muscular no encontrado'
            ], 404); // Código de respuesta 404: Not Found
        }

        // Obtener los tags asociados al grupo muscular
        $tags = $grupoMuscular->equipos;

        // Retornar los tags como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }
    public function getVideosByEquipoName($nombre)
    {
        $equipo = Equipo::where('nombre', $nombre)->first();

        if (!$equipo) {
            return response()->json([
                'success' => false,
                'message' => 'Equipo no encontrado'
            ], 404);
        }

        $videos = $equipo->videos;

        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }
}

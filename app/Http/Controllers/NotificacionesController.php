<?php

namespace App\Http\Controllers;

use App\Models\Notificaciones;
use Illuminate\Http\Request;

class NotificacionesController extends Controller
{
    public function index()
    {
        try {
            $notificaciones = Notificaciones::orderBy('fecha_envio', 'desc')->get();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Lista de notificaciones obtenida correctamente',
                'data' => $notificaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener las notificaciones: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

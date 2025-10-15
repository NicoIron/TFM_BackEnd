<?php


namespace App\Http\Controllers;

use App\Utils\ResultResponse;
use App\Models\Notificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\log;
use LDAP\Result;

class NotificacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function obtenerNotificaciones($id_usuario)
    {
        $response = new ResultResponse();
        try {
            $notificaciones = Notificaciones::with(['ticket','usuario'])
               ->where('id_usuario', $id_usuario)
               ->orderBy('fecha_creacion', 'desc')
               ->limit(50)
               ->get();
            $response->setData($notificaciones);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Notificaciones obtenidas correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener las notificaciones: ' . $e->getMessage());
            Log::error('Error al obtener las notificaciones: ' . $e->getMessage());
        }
        return response()->json($response, $response->getStatusCode());

    }


    public function contarNoLeidas($id_usuario)
    {
        $response = new ResultResponse();
        try {
            $count = Notificaciones::where('id_usuario', $id_usuario)
                ->where('leida', 0)
                ->count();
            $response->setData(['count' => $count]);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Conteo de notificaciones no leídas obtenido correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al contar las notificaciones no leídas: ' . $e->getMessage());
            Log::error('Error al contar las notificaciones no leídas: ' . $e->getMessage());
        }
        return response()->json($response, $response->getStatusCode());
    }

public function marcarComoLeida($id)
{
    $response = new ResultResponse();
    try {
        $notificacion = Notificaciones::find($id);
        if (!$notificacion) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Notificación no encontrada');
            return response()->json($response, $response->getStatusCode());
        }
        $notificacion->leida = 1;
        $notificacion->fecha_lectura = now();
        $notificacion->save();

        $response->setData($notificacion);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Notificación marcada como leída correctamente');
    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al marcar la notificación como leída: ' . $e->getMessage());
        Log::error('Error al marcar la notificación como leída: ' . $e->getMessage());
    }
    return response()->json($response, $response->getStatusCode());
}

public function marcarTodasComoLeidas($id_usuario)
{
    $response = new ResultResponse();
    try {
        $notificaciones = Notificaciones::where('id_usuario', $id_usuario)
            ->where('leida', 0)
            ->update(['leida' => 1, 'fecha_lectura' => now()]);

        foreach ($notificaciones as $notificacion) {
            $notificacion->leida = 1;
            $notificacion->fecha_lectura = now();
            $notificacion->save();
        }

        $response->setData(['count' => count($notificaciones)]);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Todas las notificaciones marcadas como leídas correctamente');
    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al marcar todas las notificaciones como leídas: ' . $e->getMessage());
        Log::error('Error al marcar todas las notificaciones como leídas: ' . $e->getMessage());
    }
    return response()->json($response, $response->getStatusCode());

}

public static function crearNotificacion($data)
{
    try {
        $notificacion = Notificaciones::create([
                'id_notificacion' => 'NOTIF-' . uniqid(),
                'id_usuario' => $data['id_usuario'],
                'id_organizacion' => $data['id_organizacion'],
                'tipo_notificacion' => $data['tipo'],
                'titulo' => $data['titulo'],
                'mensaje' => $data['mensaje'],
                'id_ticket' => $data['id_ticket'] ?? null,
                'fecha_creacion' => now()
        ]);
        Log::info("Notificación creada {$notificacion->id_notificacion} para el usuario {$data['id_usuario']}");
        return $notificacion;
    } catch (\Exception $e) {
        Log::error('Error al crear la notificación: ' . $e->getMessage());
        return null;

    }

}
}


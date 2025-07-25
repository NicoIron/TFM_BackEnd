<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

public function render($request, Throwable $exception)
{
    if ($exception instanceof ValidationException) {
        return response()->json([
            'message' => 'Datos de entrada inválidos.',
            'errors' => $exception->errors(),
        ], 422);
    }

    if ($exception instanceof NotFoundHttpException) {
        return response()->json([
            'message' => 'Ruta no encontrada.',
        ], 404);
    }

    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Error no controlado.',
            'exception' => get_class($exception),
            'details' => $exception->getMessage(), // útil en desarrollo
        ], 500);
    }

       // Controla Errores de base de datos por clave foránea o tambien conocidos como (integridad referencial)
    if ($exception instanceof QueryException) {
        // Puedes revisar el código de error específico si deseas
        if ($exception->getCode() === '23000') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el recurso porque está siendo utilizado en otra tabla.',
                'error' => $exception->getMessage()
            ], 409); // 409 Conflict
        }
    }


    return parent::render($request, $exception);
}


}

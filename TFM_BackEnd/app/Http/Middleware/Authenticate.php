<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // Siempre responder con JSON si no está autenticado
        abort(response()->json([
            'message' => 'No autenticado',
            'code' => 401
        ], 401));
    }
}

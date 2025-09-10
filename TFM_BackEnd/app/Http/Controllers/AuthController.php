<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $usuario = Usuario::with(['rol', 'organizacion', 'jerarquia'])
                ->where('email', $request->email)
                ->first();

            if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
                $response->setStatusCode(ResultResponse::ERROR_UNAUTHORIZED_CODE);
                $response->setMessage('Credenciales inválidas');
                return response()->json($response, $response->getStatusCode());
            }

            // Generar token (puedes usar JWT, Sanctum, o token básico)
            $token = $usuario->createToken('auth_token')->plainTextToken;

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Login exitoso');
            $response->setData([
                'user' => $usuario,
                'token' => $token
            ]);

            return response()->json($response, 200);

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error en el servidor: ' . $e->getMessage());
            return response()->json($response, $response->getStatusCode());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $response = new ResultResponse();
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Logout exitoso');

        return response()->json($response, 200);
    }
}

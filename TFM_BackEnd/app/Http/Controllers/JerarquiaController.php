<?php

namespace App\Http\Controllers;

use App\Models\JerarquiaInicial;
use App\Utils\ResultResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JerarquiaController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();

        try {
            $jerarquias = JerarquiaInicial::with('organizacion')->get();

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage(ResultResponse::TXT_SUCCESS_CODE);
            $response->setData($jerarquias);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage($e->getMessage());
        }

        return response()->json($response);
    }

public function guardar(Request $request)
{
    $response = new ResultResponse();

    $validator = Validator::make($request->all(), [
        'id_jerarquia'    => 'required|integer',
        'id_organizacion' => 'required|integer|exists:organizacion,id_organizacion',
        'cargo'           => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
        $response->setMessage($validator->errors()->first());
        return response()->json($response, ResultResponse::ERROR_VALIDATION_CODE);
    }

    try {
        $jerarquia = JerarquiaInicial::create($request->all());

        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage(ResultResponse::TXT_SUCCESS_CODE);
        $response->setData($jerarquia);

        return response()->json($response, ResultResponse::SUCCESS_CODE);
    } catch (\Illuminate\Database\QueryException $e) {
        // Detectar error de duplicidad
        if ($e->getCode() === '23505') {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Ya existe un registro con ese id_jerarquia.');
            return response()->json($response, ResultResponse::ERROR_CONFLICT_CODE);
        }

        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage($e->getMessage());
        return response()->json($response, ResultResponse::ERROR_INTERNAL_SERVER);
    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage($e->getMessage());
        return response()->json($response, ResultResponse::ERROR_INTERNAL_SERVER);
    }
}


    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            $jerarquia = JerarquiaInicial::with('organizacion')->find($id);

            if (!$jerarquia) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage(ResultResponse::TXT_ERROR_ELEMENT_NOT_FOUND_CODE);
                return response()->json($response);
            }

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage(ResultResponse::TXT_SUCCESS_CODE);
            $response->setData($jerarquia);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage($e->getMessage());
        }

        return response()->json($response);
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        try {
            $jerarquia = JerarquiaInicial::find($id);

            if (!$jerarquia) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage(ResultResponse::TXT_ERROR_ELEMENT_NOT_FOUND_CODE);
                return response()->json($response);
            }

            $jerarquia->update($request->all());

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage(ResultResponse::TXT_SUCCESS_CODE);
            $response->setData($jerarquia);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage($e->getMessage());
        }

        return response()->json($response);
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();

        try {
            $jerarquia = JerarquiaInicial::find($id);

            if (!$jerarquia) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage(ResultResponse::TXT_ERROR_ELEMENT_NOT_FOUND_CODE);
                return response()->json($response);
            }

            $jerarquia->delete();

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage(ResultResponse::TXT_SUCCESS_CODE);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage($e->getMessage());
        }

        return response()->json($response);
    }
}

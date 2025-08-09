<?php

namespace App\Utils;

class ResponseFormatter
{
    /**
     * Formatea una respuesta exitosa
     */
    public static function success(
        $data = null,
        string $message = 'Operación exitosa',
        int $statusCode = ResultResponse::SUCCESS_CODE
    ): array {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
            'statusCode' => $statusCode
        ];
    }

    /**
     * Formatea una respuesta de error
     */
    public static function error(
        string $message = 'Error en la operación',
        $errors = null,
        int $statusCode = ResultResponse::ERROR_CODE
    ): array {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'statusCode' => $statusCode
        ];
    }

    /**
     * Convierte un ResultResponse a array formateado
     */
    public static function fromResultResponse(ResultResponse $response): array
    {
        return [
            'success' => $response->getStatusCode() === ResultResponse::SUCCESS_CODE,
            'data' => $response->getData(),
            'message' => $response->getMessage(),
            'statusCode' => $response->getStatusCode()
        ];
    }
}

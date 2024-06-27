<?php

namespace App\Traits;

use Exception;

trait ApiResponder
{
    /**
     * Construye una respuesta exitosa.
     *
     * @param mixed $data Los datos a devolver.
     * @param int $code El código de estado HTTP.
     * @return \Illuminate\Http\JsonResponse
     */
    public function successJsonResponse(string $message,  $data, int $statusCode)
    {
        return response()->json([
            'message' => $message,
            'error' => false,
            'code' => $statusCode,
            'result' => $data
        ], $statusCode);
    }

    /**
     * Construye una respuesta de error.
     *
     * @param Exception $message El mensaje de error.
     * @param int $code El código de estado HTTP.
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(Exception $e, int $code)
    {
        return response()->json([
            'message' => $e->getMessage() . ' On file ' . $e->getFile() . ' On line ' . $e->getLine(),
            'error' => true,
            'trace' => $e->getTrace()
        ], $code);
    }
}

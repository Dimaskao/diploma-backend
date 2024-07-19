<?php

namespace App\Services\Response;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    /**
     * Standardize response data and return a JSON response.
     */
    public function response($data, $message = '', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    public function success($data = null, $message = 'Success'): JsonResponse
    {
        return $this->response($data, $message);
    }

    public function created($data = null, $message = 'Created'): JsonResponse
    {
        return $this->response($data, $message, 201);
    }

    public function badRequest($message = 'Bad Request'): JsonResponse
    {
        return $this->response(null, $message, 400);
    }

    public function unauthorized($message = 'Unauthorized'): JsonResponse
    {
        return $this->response(null, $message, 401);
    }

    public function forbidden($message = 'Forbidden'): JsonResponse
    {
        return $this->response(null, $message, 403);
    }

    public function notFound($message = 'Not Found'): JsonResponse
    {
        return $this->response(null, $message, 404);
    }

    public function internalServerError($message = 'Internal Server Error'): JsonResponse
    {
        return $this->response(null, $message, 500);
    }
}

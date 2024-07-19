<?php

namespace App\Services\Response;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    /**
     * Standardize response data and return a JSON response.
     */
    public function response($data = [], $statusCode = 200, $message = 'Success'): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    public function success($data = [], $statusCode = 200, $message = 'Success'): JsonResponse
    {
        return $this->response($data, $statusCode, $message);
    }

    public function notFound($message = 'Not Found', $data = []): JsonResponse
    {
        return $this->response($data, 404, $message);
    }

    public function unauthenticated($message = 'Unauthenticated', $data = []): JsonResponse
    {
        return $this->response($data, 401, $message);
    }

    public function created($message = 'Created', $data = []): JsonResponse
    {
        return $this->response($data, 201, $message);
    }

    public function internalServerError($message = 'Internal Server Error', $data = []): JsonResponse
    {
        return $this->response($data, 500, $message);
    }

    public function badRequest($message = 'Bad request', $data = []): JsonResponse
    {
        return $this->response($data, 400, $message);
    }
}

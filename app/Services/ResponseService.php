<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    /**
     * Handle response data and return a JSON response.
     */
    public function response($responseKey, $data, $statusCode): JsonResponse
    {
        return response()->json([$responseKey => $data], $statusCode);
    }
}

<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Return a success json response.
     * 
     * @param string $message
     * @param mixed $data
     * @param int $status
     * 
     * @return JsonResponse
     */
    public static function success(string $message, mixed $data, int $status = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Return an error json response.
     * 
     * @param string $message
     * @param int $status
     * 
     * @return JsonResponse
     */
    public static function error(string $message, int $status = 400)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $status);
    }
}

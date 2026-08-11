<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    private int $status;
    private string $message;
    private mixed $data;

    public function __construct(int $status, string $message, mixed $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Return a success json response.
     * 
     * @param string $message
     * @param mixed $data
     * @param int $status
     * 
     * @return ApiResponse
     */
    public static function success(string $message, mixed $data, int $status = 200)
    {
        return new self($status, $message, $data);
    }

    /**
     * Return an error json response.
     * 
     * @param string $message
     * @param int $status
     * 
     * @return ApiResponse
     */
    public static function error(string $message, int $status = 400)
    {
        return new self($status, $message);
    }

    /**
     * Return an array response.
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * Return a json response.
     * 
     * @return JsonResponse
     */
    public function toJsonResponse()
    {
        return response()->json([
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ], $this->status);
    }
}

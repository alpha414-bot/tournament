<?php

namespace App\Helpers;


class ResponseHelper
{
    public static function response_success($message, $data)
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public static function response_error($message, $data)
    {
        return response()->json([
            'code' => 400,
            'message' => $message,
            'data' => $data,
        ], 400);
    }

    public static function response_unprocessable_entity($message, $errors)
    {
        return response()->json([
            'code' => 422,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }
    
    public static function response_unauthorized($message, $errors)
    {
        return response()->json([
            'code' => 401,
            'message' => $message,
            'errors' => $errors,
        ], 401);
    }

    public static function response_created($message, $data)
    {
        return response()->json([
            'code' => 201,
            'message' => $message,
            'data' => $data,
        ], 201);
    }

    
}
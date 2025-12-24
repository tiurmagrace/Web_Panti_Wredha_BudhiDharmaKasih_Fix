<?php

/**
 * =================================================================
 * FILE: app/Helpers/ResponseHelper.php
 * =================================================================
 */

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public static function error($message = 'Error', $errors = null, $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public static function validationError($errors, $message = 'Validation failed')
    {
        return self::error($message, $errors, 422);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, null, 401);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, null, 403);
    }

    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, null, 404);
    }
}



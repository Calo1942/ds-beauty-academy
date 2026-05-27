<?php

namespace DsBeautyAcademy\Helpers;

trait ApiResponse
{
    public static function success($code = 200, $message, $data = null)
    {
        return [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        exit;
    }
    public static function error($code = 400, $message, $error = null)
    {
        return [
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'error' => $error
        ];
        exit;
    }
}
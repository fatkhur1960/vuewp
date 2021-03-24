<?php
class ApiResult
{
    public static function error($message = '', $data = '')
    {
        return new WP_Error('error', __($message), $data);
    }

    public static function success($data, $message = '')
    {
        return new WP_REST_Response([
            'code' => 'success',
            'message' => $message,
            'data' => $data,
        ]);
    }
}

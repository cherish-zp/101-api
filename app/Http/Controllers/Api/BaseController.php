<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected function success($data = [], $code = 200, $msg = 'success')
    {
        return response()->json([
            'message' => $msg,
            'code'    => $code,
            'data'    => $data,
        ]);
    }

    protected function error($data = [], $code = 422, $msg = 'error')
    {
        return response()->json([
            'message' => $msg,
            'code'    => $code,
            'data'    => $data,
        ]);
    }
}

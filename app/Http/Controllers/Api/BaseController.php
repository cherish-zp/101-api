<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected function responseSuccess($data = [], $code = 200, $msg = 'success')
    {
        return response()->json([
            'message' => $msg,
            'code' => $code,
            'data' => $data,
        ]);
    }
}

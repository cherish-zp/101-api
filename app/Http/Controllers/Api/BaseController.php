<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $error;

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
        if (!empty($this->error)) {
            $data = [
                'Message'           => $this->error->getMessage(),
                'TraceAsString'     => $this->error->getTraceAsString(),
                'Trace'             => $this->error->getTrace(),
                'Previous'          => $this->error->getPrevious(),
            ];
        }

        return response()->json([
            'message' => $msg,
            'code'    => $code,
            'data'    => $data,
        ]);
    }
}

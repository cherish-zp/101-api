<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IntegerRepeatRequest;
use App\Models\CoinRepeat;
use App\Models\SystemSetting;
use App\Models\UserAssets;
use Illuminate\Http\Request;

/**
 * 释放
 * Class RepeatController
 * @package App\Http\Controllers\Api
 */
class RepeatController extends BaseController
{
    /**
     * 静态释放 ==> 资产加速释放成积分
     * @param IntegerRepeatRequest $integerRepeatRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function integralRepeat(IntegerRepeatRequest $integerRepeatRequest)
    {
        try {
            $userId = \JWTAuth::user()->uid;
            CoinRepeat::integralRepeat($userId,$integerRepeatRequest->integral);

        } catch (\Exception $e) {

            return $this->error([],422,$e->getMessage());
        }
    }
}

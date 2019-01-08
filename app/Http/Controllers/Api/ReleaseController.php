<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IntegerRepeatRequest;
use App\Models\CoinRepeat;
use App\Models\SystemSetting;
use App\Models\UserAssets;
use Illuminate\Http\Request;

/**
 * 释放 : 资产释放 ==> 积分
 * Class RepeatController
 * @package App\Http\Controllers\Api
 */
class ReleaseController extends BaseController
{
    /**
     * 静态释放 ==> 资产加速释放成积分
     * @param IntegerRepeatRequest $integerRepeatRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function assetsReleaseToIntegral(IntegerRepeatRequest $integerRepeatRequest)
    {
        try {
            $userId = \JWTAuth::user()->uid;
            CoinRepeat::assetsReleaseToIntegral($userId,$integerRepeatRequest->integral);
        } catch (\Exception $e) {
            $this->error = $e;
            return $this->error([],422,$e->getMessage());
        }
    }
}

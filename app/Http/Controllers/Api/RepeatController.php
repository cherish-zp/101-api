<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IntegerRepeatRequest;
use App\Models\CoinRepeat;
use App\Models\SystemSetting;
use App\Models\UserAssets;

/**
 * Class RepeatController.
 */
class RepeatController extends BaseController
{
    /**
     * @param IntegerRepeatRequest $integerRepeatRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function integralRepeat(IntegerRepeatRequest $integerRepeatRequest)
    {
        try {
            $userId = \JWTAuth::user()->uid;
            //积分币种名称
            $integralCoinName = SystemSetting::getFieldValue(SystemSetting::$integralCoinName);
            //资产币种名称
            $assetsCoinName = SystemSetting::getFieldValue(SystemSetting::$assetsCoinName);
            //用户资产币种
            //$userAssets = UserAssets::getUserAssetsUserIdAndCoinName($userId,$assetsCoinName);

            CoinRepeat::integralRepeat($userId, $integerRepeatRequest->integral);
        } catch (\Exception $e) {
            return $this->error([], 422, $e->getMessage());
        }
    }
}

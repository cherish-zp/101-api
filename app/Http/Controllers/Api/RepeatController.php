<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IntegerRepeatRequest;
use App\Http\Requests\RepeatRequest;
use App\Models\CoinRepeat;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserAssets;
use App\Models\UserFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class RepeatController
 * @package App\Http\Controllers\Api
 */
class RepeatController extends BaseController
{
    /**
     * @param RepeatRequest $repeatRequest
     * @throws \Exception
     */
    public function index(RepeatRequest $repeatRequest)
    {
        try {
            DB::beginTransaction();
                $uid = $this->user->uid;
                //积分复投放大倍数
                $gain = SystemSetting::getFieldValue(SystemSetting::$queueCompleteAssetGain);
                $integralCoin = SystemSetting::getIntegralCoin();
                $assetsCoin = SystemSetting::getAssetsCoin();
                //积分放到成资产的数量
                $gainNum = bcmul($repeatRequest->integral,$gain);

                //用户积分减少增加
                UserAssets::assetsReduce($uid,$integralCoin,$repeatRequest->integral);
                //资产增加
                UserAssets::assetsAdd($uid,$assetsCoin,$gainNum);
                //添加复投记录
                $coinRepeat = CoinRepeat::createData($uid,$repeatRequest->integral,$gainNum);
                $title = '积分复投';
                //增加积分流水
                $integralAssets = UserAssets::getUserAssetsUserIdAndCoinName($uid,$integralCoin);
                UserFlow::createFlow($uid,$title, $integralAssets->available
                   , bcsub($integralAssets->available,$repeatRequest->integral), -$repeatRequest->integral
                    , $integralAssets->cid, $integralCoin, $coinRepeat->trade_no, UserFlow::$integralRepeat);
                //增加资产流水
                $assetsAssets = UserAssets::getUserAssetsUserIdAndCoinName($uid,$assetsCoin);
                UserFlow::createFlow($uid,$title, $assetsAssets->available
                    , bcadd($assetsAssets->available,$gainNum), $gainNum, $assetsAssets->cid, $assetsCoin
                    , $coinRepeat->trade_no, UserFlow::$integralRepeat);
                //更新公排


            DB::commit();


        } catch (\Exception $e) {
            DB::rollBack();
            $this->error = $e;
            $this->error([],422,$e->getMessage());
        }
    }
}

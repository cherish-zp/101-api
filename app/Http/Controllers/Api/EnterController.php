<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserEnterPost;
use App\Models\Queue;
use App\Models\QueueRecord;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserAssets;
use App\Models\UserFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnterController extends BaseController {

    /**
     * 用户开始排队
     *
     * ** @SWG\Post(
     *     path="/queue",
     *     tags={"Enter"},
     *     summary="排队",
     *     produces={"application/json"},
     *     security={{ "Bearer":{}}},
     *   @SWG\Parameter(
     *      in="query",
     *      name="type",
     *      type="string",
     *      default="",
     *      description="type 为 1,2,3 ",
     *      required=true
     *   ),
     *   @SWG\Response(
     *      response="200",
     *      description="成功"
     *   ),
     *   @SWG\Response(
     *      response="422",
     *      description="验证失败"
     *   ),
     * )
     *
     *
     *
     *  此时用户还有没进场成功
     * @param UserEnterPost $userEnterRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function queue(UserEnterPost $userEnterRequest)
    {
        try {
            $uid = JWTAuth::user()->uid;

            if (User::isOut($uid))
                throw new \Exception('你已出局,无法排队');

            //根据等级类型查询数量
            $field = SystemSetting::getSystemField(SystemSetting::$levelPrefix , $userEnterRequest->type , SystemSetting::$usdtSuffix);
            $num = SystemSetting::getFieldValue($field);
            $usdtName = SystemSetting::getFieldValue(SystemSetting::$queueAssetCoinName);

            DB::beginTransaction();

                //判断用户usdt资产是否足够
                $usdtInfo = UserAssets::whereUid($uid)->whereCoinName($usdtName)->lockForUpdate()->first();

                if ($usdtInfo->available < $num )
                    throw new \Exception($usdtName . ' 不足,无法排队');

                //减少用户资产
                $usdtInfo->available = bcsub($usdtInfo->available  , $num);
                $res = $usdtInfo->save();
                if (!$res)
                    throw new \Exception('user assets reduce fail');

                $queueRecordData = [
                    'trade_no'  => getOrderTradeOn(),
                    'uid'       => $uid,
                    'level'     => $userEnterRequest->type,
                    'num'       => $num,
                    'status'    => QueueRecord::$statusNo
                ];
                //插入记录表
                $queueRecode = QueueRecord::createRecode($queueRecordData);

                $queueData = [
                    'uid'       =>$uid,
                    'level'     =>$userEnterRequest->type,
                    'num'       => $num,
                    'status'    => Queue::$statusNo
                ];
                //插入排队表
                Queue::createQueue($queueData);

                $userAddress = UserAddress::getAddressByUserIdCoinId($uid,$usdtInfo->cid);

                $intoAccount = SystemSetting::getFieldValue(SystemSetting::$systemAccountName);
                $outAccount = $userAddress;
                $title = '排队扣除usdt';
                $beforeNum = $usdtInfo->available;

                $afterNum = bcsub($usdtInfo->available,$num);
                $num = -$num;
                $cid = $usdtInfo->cid;
                $coinName = $usdtInfo->coin_name;

                $resourceId = $queueRecode->trade_no;
                $type = 1;      //排队

                UserFlow::createFlow($uid, $title, $beforeNum, $afterNum, $num, $cid
                    , $coinName, $resourceId, $type);

            DB::commit();

            return $this->success([],200,'操作成功');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error = $e;
            return $this->error([],422 , $e->getMessage());
        }
    }


    public function enter() {

    }

}

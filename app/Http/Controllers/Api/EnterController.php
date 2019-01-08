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
     * 用户开始排队  == 同时投资金额
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

            if (User::isQueued($uid)) {
                throw new \Exception('已进场,无法追投');
            }
            //usdt name
            $usdtName = SystemSetting::getFieldValue(SystemSetting::$queueAssetCoinName);
            //根据等级类型查询数量
            $levelUsdtNum = SystemSetting::getLevelNeedUsdt($userEnterRequest->type);

            //用户审计还差的usdt
            $userDiffUsdt = $this->userDiffUsdt($uid,$levelUsdtNum);
            DB::beginTransaction();

                //判断用户usdt资产是否足够
                $usdtInfo = UserAssets::whereUid($uid)->whereCoinName($usdtName)->lockForUpdate()->first();

                if ($usdtInfo->available < $userDiffUsdt )
                    throw new \Exception($usdtName . ' 不足,无法排队');

                //减少用户资产
                $usdtInfo->available = bcsub($usdtInfo->available  , $userDiffUsdt);
                $res = $usdtInfo->save();
                if (!$res)
                    throw new \Exception('user assets reduce fail');

                //插入记录表
                $queueRecode = QueueRecord::createRecode([
                    'trade_no'  => getOrderTradeOn(),
                    'uid'       => $uid,
                    'level'     => $userEnterRequest->type,
                    'num'       => $userDiffUsdt,
                    'status'    => QueueRecord::$statusNo
                ]);

                //插入排队表
                Queue::createQueue([
                    'uid'       =>$uid,
                    'level'     =>$userEnterRequest->type,
                    'num'       => $userDiffUsdt,
                    'status'    => Queue::$statusNo
                ]);

                //记录流水
                UserFlow::createFlow($uid, '排队扣除usdt', $usdtInfo->available, bcsub($usdtInfo->available,$userDiffUsdt)
                    , -$userDiffUsdt, $usdtInfo->cid, $usdtInfo->coin_name, $resourceId = $queueRecode->trade_no, UserFlow::$queueStatus);

                //查询上级推荐人id

                if ($inviteUid = User::getInviteUid($uid) != 0) {
                    //查询推荐人是否有推荐奖励
                    if (User::isHaveDynamicRewards($inviteUid)) {
                        //推荐人资产增加等额的usdt  + 记录流水
                        //等值 usdt 加入 推荐人的资产中
                        $assetsCoinValue = SystemSetting::getFieldValue(SystemSetting::$assetsCoinName);
                        //查询必须在插入前
                        $inviteUserAssets = UserAssets::getUserAserIdAndCoinName($inviteUid,$assetsCoinValue);

                        UserAssets::assetsAdd($inviteUid,$assetsCoinValue,$userDiffUsdt);
                        $title = $inviteUid . ' 接受 ' . $uid . ' 推荐奖 ';

                        //添加用户流水
                        UserFlow::createFlow($uid,$title, $inviteUserAssets->available
                            , bcadd($inviteUserAssets->available,$userDiffUsdt), $userDiffUsdt, $inviteUserAssets->cid
                            , SystemSetting::$assetsCoinName, $queueRecode->trade_no
                            , UserFlow::$recommendReword);
                    }
                }
            DB::commit();

            return $this->success([],200,'操作成功');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error = $e;
            return $this->error([],422 , $e->getMessage());
        }
    }

    /**
     * 排队等级币种信息
     * @param Request $request
     */
    public function queueLevelCoinInfo(Request $request)
    {
        $uid = JWTAuth::user()->uid;
        $level = 1;
        $this->levelNeedUsdt($uid,$level);
    }

    /**
     * 用户排队升级所需要的usdt
     * @param $uid
     * @param $levelUsdtNum
     * @return string
     * @throws \Exception
     */
    public function userDiffUsdt($uid,$levelUsdtNum)
    {
        $hasQueueNum = Queue::hasQueueNum($uid);
        if (($levelUsdtNum - $hasQueueNum)  <= 0)
            throw new \Exception('已排队USDT数量和所选升级选项不匹配');
        return bcsub($levelUsdtNum,$hasQueueNum);
    }

    public function enter() {
        
    }

}

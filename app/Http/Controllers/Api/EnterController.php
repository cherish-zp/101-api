<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserEnterPost;
use App\Models\Queue;
use App\Models\QueueRecord;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserAssets;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnterController extends BaseController {

    /**
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
     * 用户排队
     * @param UserEnterPost $userRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function queue(UserEnterPost $userRequest)
    {
        try {
            $userId = JWTAuth::user()->user_id;

            if (User::isOut($userId))
                throw new \Exception('你已出局,无法排队');

            //根据等级类型查询数量
            $field = SystemSetting::getSystemField(SystemSetting::$levelPrefix , $userRequest->type , SystemSetting::$usdtSuffix);
            $num = SystemSetting::getFieldValue($field);

            $queueRecordData = [
                'trade_no'  =>  getOrderTradeOn(),
                'uid'       => $userId,
                'level'     => $userRequest->type,
                'num'       => $num,
                'status'    => QueueRecord::$statusNo
            ];
            QueueRecord::createRecode($queueRecordData);

            $queueData = [
                'uid'       => $userId,
                'num'       => $num,
                'status'    => Queue::$statusNo
            ];
            Queue::createQueue($queueData);



        } catch (\Exception $e) {
            $this->error = $e;
            return $this->error([],422 , $e->getMessage());
        }
    }


    public function enter() {

    }

}

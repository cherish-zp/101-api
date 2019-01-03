<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserEnterPost;
use App\Models\SystemSetting;
use App\Models\UserAssets;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class userEnterController extends BaseController {

    /**
     * 用户进场
     */
    public function enter(UserEnterPost $userRequest) {
        try {
            $userId = JWTAuth::user()->user_id;
            $levelIntegral = SystemSetting::getFieldValue(SystemSetting::$levelPrefix . $userRequest->type);
            if (!is_numeric($levelIntegral) || $levelIntegral <= 0)
                throw new \Exception('系统配置异常.');
            $assets = UserAssets::getUserAssets($userId);


        } catch (\Exception $e) {
            return $this->error([],422 , $e->getMessage());
        }
    }

}

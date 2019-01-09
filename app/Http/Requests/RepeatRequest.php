<?php

namespace App\Http\Requests;


use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserAssets;

class RepeatRequest extends Base
{
    /**
     * Get the lidation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'integral'=>[
                'required','numeric'
                ,function($attribute, $value, $fail){
                    if (!preg_match('/^[0-9]+(.[0-9]{1,4})?$/', $value)) {
                        return $fail($attribute.' 积分不能多于4个小数点!');
                    }
                    $uid = \JWTAuth::user()->uid;
                    $integralCoinName = SystemSetting::getFieldValue(SystemSetting::$integralCoinName);
                    if (UserAssets::where(['uid'=>$uid,'coin_name'=>$integralCoinName])->value('available') < $value) {
                        return $fail('积分不足!');
                    }
            }],
        ];
    }
}

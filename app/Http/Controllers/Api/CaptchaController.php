<?php

namespace App\Http\Controllers\Api;

use App\Api\Requests\Captcha as CaptchaRequest;
use App\Models\Captcha;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class CaptchaController extends BaseController
{
    /**
     * @SWG\Post(path="/captcha/sms",
     *   tags={"Captcha"},
     *   summary="发送短信验证码",
     *   produces={"application/json"},
     *   security={{ "Bearer":{}}},
     *   description="类型：signup=注册，reset_pwd=忘记密码，reset_pay_pwd=找回支付密码, coin=增加提币地址, mention=提币  <br />说明：在登录的状态可以不填写区号和手机号 <br> 测试验证码固定888888",
     *     @SWG\Parameter(
     *     in="query",
     *     name="mobile",
     *     type="string",
     *     description="账号",
     *     required=false
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="type",
     *     type="string",
     *     description="类型",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="area_code",
     *     type="string",
     *     description="区号",
     *     required=false
     *   ),
     *   @SWG\Response(
     *     response="200",
     *     description="成功"
     *   ),
     *   @SWG\Response(
     *     response="422",
     *     description="验证失败"
     *   )
     * )
     *
     * @param CaptchaRequest $request
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return mixed
     */
    public function sms(CaptchaRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $mobile = $user ? $user->mobile : $request->get('mobile');
        $type = $request->get('type');
        $area_code = $user ? $user->area_code : $request->get('area_code');

        if (!$area_code) {
            $area_code = \App\Models\User::where('mobile', $mobile)->value('area_code');
            if (!$area_code) {
                return $this->error([], 422, '没有找到注册信息');
            }
        }
        $code = mt_rand(100000, 999999);
        $type_msg = Captcha::getSmsType($type);
        $msg = "【101】验证码:{$code},您正在{$type_msg}。10分钟内有效，请勿告知他人。";

        //发送短信
        $res = Captcha::sendSms($area_code.$mobile, $msg);
        if (!$res) {
            $this->error([], 422, '发送失败');
        }
        Cache::put("sms_{$type}_code:".$mobile, $code, 10);

        return $this->success();
    }

    /**
     * 发送邮件验证码
     *
     * @param \App\Api\V1\Requests\Captcha $request
     *
     * @return array
     */
    public function email(CaptchaRequest $request)
    {
        $email = $request->get('email');
        $type = $request->get('type');
        $code = mt_rand(100000, 999999);
        $type_msg = Captcha::getSmsType($type);
        $msg = "【】验证码:{$code},您正在{$type_msg}。10分钟内有效，请勿告知他人。";
        $flag = Mail::raw($msg, function ($message) use ($email) {
            $message->to($email)->subject('注册');
        });
        $flag && $this->response->error('发送失败', 500);

        Cache::put("email_{$type}_code:".$email, $code, 10);

        return $this->respondSuccess();
    }
}

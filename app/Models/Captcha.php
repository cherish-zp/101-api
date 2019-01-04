<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Captcha
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Captcha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Captcha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Captcha query()
 * @mixin \Eloquent
 */
class Captcha extends Base
{
    //短信平台请求接口
    const API_SEND_URL = 'http://intapi.253.com/send/json?';

    const API_ACCOUNT = 'I6461460';//短信平台账户

    const API_PASSWORD = 'grTNSUHX9Pf568';//短信平台密码

    /**
     * 验证短信验证码
     * @param $mobile
     * @param $code
     * @param string $type
     * @param bool $is_rm
     * @return bool
     */
    public static function VerifySms($mobile, $code, $type = 'signup', $is_rm = false)
    {
        $sms_code = Cache::get("sms_{$type}_code:{$mobile}");
        if(!$sms_code || $sms_code != $code) {
            return false;
        }
        if($is_rm) {
            Cache::delete("sms_{$type}_code:{$mobile}");
        }
        return true;
    }

    /**
     * 验证短信验证码
     * @param $mobile
     * @param $code
     * @param string $type
     * @param bool $is_rm
     * @return bool
     */
    public static function VerifyEmail($email, $code, $type = 'signup', $is_rm = false)
    {
        $email_code = Cache::get("email_{$type}_code:{$email}");
        if(!$email_code || $email_code != $code) {
            return false;
        }
        if($is_rm) {
            Cache::delete("email_{$type}_code:{$email}");
        }
        return true;
    }

    /**
     * 发送短信验证码
     * @param $mobile
     * @param $msg
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendSms($mobile, $msg)
    {
        $postArr = array (
            'account'  =>  self::API_ACCOUNT,
            'password' => self::API_PASSWORD,
            'msg' => $msg,
            'mobile' => $mobile
        );

        $client = new Client();
        $res = $client->request('POST', self::API_SEND_URL, [
            \GuzzleHttp\RequestOptions::JSON => $postArr
        ]);
        $result = json_decode($res->getBody()->getContents(), true);

        if(!isset($result['code']) || $result['code'] > 0) {
            return false;
        }
        return true;
    }

    public static function getSmsType($type = null)
    {
        $data = [
            'signup' => '注册',
            'reset_pwd' => '重置密码',
            'reset_pay_pwd' => '找回支付密码',
            'coin' => '编辑提币地址',
            'mention' => '提币',
            /*'set_pay_pwd' => '重置交易密码',
            'replace_mobile' => '更换手机',
            'auth_mobile' => '手机认证',
            'auth_email' => '邮箱认证'*/
        ];
        return $type !== null ? $data[$type] : array_keys($data);
    }

    /**
     * 验证token
     * @param $str
     * @param $uid
     * @param $type
     * @return bool
     */
    public static function VerifyToken($str, $uid, $type)
    {
        $key = "verify_{$type}:{$uid}";
        $token = Cache::get($key);
        if(!$token || $token != $str) {
            return false;
        }
        Cache::delete($key);
        return true;
    }
}

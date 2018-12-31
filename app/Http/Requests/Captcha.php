<?php

namespace App\Api\Requests;

use Illuminate\Validation\Rule;

class Captcha extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        switch ($this->method()){
            case 'GET':
                $rules['mobile'] = ['required', 'regex:/^1[3456789]\d{9}$/'];
                break;
            case 'POST':
                if($this->route()->getActionMethod() == 'sms') {
                    $rules['area_code'] = ['digits_between:1,6'];
                    if(in_array($this->get('type'), ['signup', 'reset_pay_pwd'])) {
                        $rules['mobile'] = ['required', 'digits_between:5,20'];
                        if($this->get('type') == 'signup' && !$this->get('area_code')) {
                            $this->error('请选择区号');
                        }
                    }
                    $rules['type'] = ['required', Rule::in(\App\Models\Captcha::getSmsType())];
                }
                break;
        }
        return $rules;
    }

    /**
     * 获取已定义的验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {

        return [
            'required' => '缺少必要参数',
            'mobile.digits_between' => '手机号格式有误',
            'type.in' => '类型有误',
            'email.email' => '邮箱格式有误',
        ];
    }
}
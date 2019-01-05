<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;

class UsersPost extends Base
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        switch ($this->method()) {
            case 'POST':
                $actionMethod = $this->route()->getActionMethod();
                if ($actionMethod == 'signUp') {
                    $rules = [
                        'mobile'     => 'required|digits_between:5,20|unique:user,mobile',
                        'login_pass' => 'required|min:6|max:32',
                    ];
                }
                if ($actionMethod == 'signIn') {
                    $rules = [
                        'mobile'     => 'required|digits_between:5,20',
                        'login_pass' => 'required|min:6|max:32',
                    ];
                }
                if ($actionMethod == 'passwordReset') {
                    $rules = [
                        'login_pass_old' => 'required|min:6|max:32',
                        'login_pass_new' => ['required', 'min:6', 'max:32'],
                    ];
                }
                if ($actionMethod == 'forgetPassword') {
                    $rules = [
                        'mobile'    => 'required|digits_between:5,20',
                        'login_pass'=> 'required|min:6|max:32',
                        'code'      => 'required',
                    ];
                }
                break;
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'mobile.required'      => '手机号不能为空.',
            'mobile.unique'        => '手机号已被注册.',
            'login_pass.required'  => '密码不能为空.',
            'mobile.digits_between'=> '手机号规则错误',
            'login_pass.min'       => '密码规则错误.',
            'login_pass.max'       => '密码规则错误.',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthenticationException('该帐号已被拉黑');
    }
}

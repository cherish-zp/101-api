<?php

namespace App\Api\Requests;

use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Http\FormRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
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
     * 处理验证异常消息.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ResourceException($validator->errors()->first());
    }

    protected function failedAuthorization()
    {
        throw new AuthenticationException('帐号停用');
    }

    /**
     * 自定义错误.
     *
     * @param $message
     */
    protected function error($message)
    {
        throw new ResourceException($message);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class Base extends FormRequest
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
        return [
            //
        ];
    }

    /**
     *  配置验证器实例。
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        header('content-type:application/json;charset=utf8');
        $validator->after(function ($validator) {
            $errors = $validator->errors()->toArray();
            if ($errors != []) {
                echo json_encode([
                    'message' => current(current($errors)),
                    'code' => 422,
                    'data' => [],
                ]);
                exit;
            }
        });
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IntegerRepeatRequest extends Base
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'integral'=>['required','numeric'],
        ];
    }
}

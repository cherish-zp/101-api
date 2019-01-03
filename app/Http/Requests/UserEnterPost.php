<?php

namespace App\Http\Requests;

use App\Api\Requests\Request;
use App\Models\SystemSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserEnterPost extends Base
{

    public function rules()
    {
        return [
            'type' => ['required',Rule::in(SystemSetting::$levelType)],
        ];
    }
}

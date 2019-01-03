<?php

namespace App\Http\Requests;

use App\Models\SystemSetting;
use Illuminate\Validation\Rule;

class UserEnterPost extends Base
{
    public function rules()
    {
        return [
            'type' => ['required', Rule::in(SystemSetting::$levelType)],
        ];
    }
}

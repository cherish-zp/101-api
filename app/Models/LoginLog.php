<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Base
{
    use Uuids;
    protected $table = 'login_log';
    protected $guarded = [];

    protected $hidden = ['uid'];
}

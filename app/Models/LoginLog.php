<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LoginLog
 *
 * @property string $id
 * @property int $uid
 * @property string $login_date
 * @property string|null $ip
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereLoginDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LoginLog whereUserAgent($value)
 * @mixin \Eloquent
 */
class LoginLog extends Base
{
    use Uuids;
    protected $table = 'login_log';
    protected $guarded = [];

    protected $hidden = ['uid'];
}

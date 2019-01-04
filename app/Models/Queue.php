<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Queue
 *
 * @property string $uuid uuid
 * @property int $uid 用户id
 * @property int $level 排队等级
 * @property float $num 总投数量 usdt
 * @property int $status 1=已完成|2=待完成
 * @property string|null $enter_time 进场时间
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereEnterTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue whereUuid($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Queue uuid($uuid, $first = true)
 */
class Queue extends Model
{
    use Uuids;
    public $table = 'queue';

    public $fillable = [
        'uid','num','status'
    ];

    /**
     * @var int 已完成
     */
    public static $statusYes = 1;
    /**
     * @var int 未完成
     */
    public static $statusNo  = 2;

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function createQueue($data)
    {
        $queue = self::whereUid($data['uid'])->lockForUpdate()->first();
        if (!$queue) {
            $res = self::create($data);
            if (!$res)
                throw new \Exception('queue create fail ');
        } else {
            throw new \Exception('你已在排队中');
        }
        return true;
    }
}

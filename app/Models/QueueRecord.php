<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\QueueRecord
 *
 * @property int $id id
 * @property string $trade_no 交易单号
 * @property int $uid 用户id
 * @property int $level 排队等级
 * @property float $num 追加数量 usdt
 * @property int $status 1=已完成|2=待支付
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QueueRecord extends Base
{
    use Uuids;
    public $table = 'queue_record';

    public $fillable = [
        'trade_no','uid','level','num','status'
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
     * 插入排队记录
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function createRecode($data)
    {
        $queueRecode = self::create($data);
        if (!$queueRecode)
            throw new \Exception('排队记录插入失败');

        return $queueRecode;
    }
}

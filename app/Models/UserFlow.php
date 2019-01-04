<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\UserFlow
 *
 * @property string $uuid uuid
 * @property int $uid uid
 * @property string $into_account 入账账户
 * @property string $out_account 出账账户
 * @property string $title 标题
 * @property float $before_num 资产之前数量
 * @property float $after_num 资产之后数量
 * @property float $num 操作适量
 * @property int $cid coin_id
 * @property string $coin_name 币名
 * @property string $resource_id 关联操作表id
 * @property int $type 1=2=3=4
 * @property string $callback 信息记录
 * @property string $callback_time 回调时间
 * @property int $callback_status 1=成功|2=等待回调|3=失败
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereAfterNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereBeforeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCallback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCallbackStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCallbackTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCoinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereIntoAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereOutAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereUuid($value)
 * @mixin \Eloquent
 */
class UserFlow extends Base
{

    use Uuids;
    protected $table = 'user_flow';
    protected $guarded = [];

    /**
     * @param $uid 用户id
     * @param $intoAccount 转入账户
     * @param $outAccount 转出账户
     * @param $title 标题
     * @param $beforeNum 之前数量
     * @param $afterNum 之后数量
     * @param $num 操作数量
     * @param $cid 币种id
     * @param $coinName 币种名称
     * @param $resourceId 关联订单id
     * @param $type 类型
     */
    public static function createFlow($uid, $intoAccount, $outAccount, $title, $beforeNum, $afterNum, $num, $cid, $coinName, $resourceId, $type)
    {
        $flow = [
            'uid' => $uid,
            'into_account' => $intoAccount,
            'out_account' => $outAccount,
            'title' => $title,
            'before_num' => $beforeNum,
            'after_num' => $afterNum,
            'num' => $num,
            'cid' => $cid,
            'coin_name' => $coinName,
            'resource_id' => $resourceId,
            'type' => $type,
        ];
        self::create($flow);

    }
}

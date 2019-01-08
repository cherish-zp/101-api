<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;



/**
 * App\Models\UserFlow
 *
 * @property int $id id
 * @property int $uid uid
 * @property string $into_account 入账账户
 * @property string $out_account 出账账户
 * @property string $title 标题
 * @property float $before_num 资产之前数量
 * @property float $after_num 资产之后数量
 * @property float $num 操作数量
 * @property int $cid coin_id
 * @property string $coin_name 币名
 * @property string $resource_id 关联操作表id
 * @property int $type 1= 排队
 * @property string $callback_msg 回调 信息记录
 * @property int|null $callback_count 回调次数
 * @property string|null $callback_time 回调时间
 * @property int $callback_status 0=等待回调 | 1=成功 | 2=失败
 * @property int $request_count 请求python 次数
 * @property string|null $request_time 请求python 时间
 * @property int|null $request_status 1=成功 | 2=失败 | 0:默认  请求python 状态
 * @property string $request_msg 请求信息
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereAfterNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereBeforeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCallbackCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCallbackMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCallbackStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCallbackTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCoinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereIntoAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereOutAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereRequestCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereRequestMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereRequestStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereRequestTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFlow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserFlow extends Base
{

    use Uuids;
    protected $table = 'user_flow';
    protected $guarded = [];

    /**
     * @var int 排队类型
     */
    public static $queueStatus = 1;

    /**
     * @param $uid |用户id
     * @param $title  |标题
     * @param $beforeNum |之前数量
     * @param $afterNum |之后数量
     * @param $num |操作数量
     * @param $cid |币种id
     * @param $coinName |币种名称
     * @param $resourceId |关联订单id
     * @param $type |类型
     * @return UserFlow|array|Model
     * @throws \Exception
     */
    public static function createFlow($uid,$title, $beforeNum, $afterNum, $num, $cid, $coinName, $resourceId, $type)
    {
        $flow = [
            'uid' => $uid,
            'title' => $title,
            'before_num' => $beforeNum,
            'after_num' => $afterNum,
            'num' => $num,
            'cid' => $cid,
            'coin_name' => $coinName,
            'resource_id' => $resourceId,
            'type' => $type,
        ];
        $flow = self::create($flow);
        if (!$flow)
            throw new \Exception('flow insert error');
        return $flow;
    }
}

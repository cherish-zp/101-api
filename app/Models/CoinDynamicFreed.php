<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CoinDynamicFreed
 *
 * @property int $id id
 * @property int $uid 用户id
 * @property float $freed_assets_num 释放资产数量
 * @property float $before_integral 之前积分
 * @property float $after_integral 之后积分
 * @property float $before_assets 之前资产
 * @property float $after_assets 之后资产
 * @property string $date 日期
 * @property int $status 1:已领取|2:未领取
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereAfterAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereAfterIntegral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereBeforeAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereBeforeIntegral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereFreedAssetsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $body 数据
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinDynamicFreed whereBody($value)
 */
class CoinDynamicFreed extends Model
{
    use Uuids;

    protected $table = 'coin_dynamic_freed';

    protected $guarded = [];
}

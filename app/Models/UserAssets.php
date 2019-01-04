<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\UserAssets
 *
 * @property int $id id
 * @property int $uid 用户
 * @property int $cid 币种id
 * @property string $coin_name 资产代码
 * @property float $available 可用数量
 * @property float $freeze 冻结数量
 * @property float $price 当前价格
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereCoinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereFreeze($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAssets whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserAssets extends Base
{
    use Uuids;
    protected $table = 'user_assets';

    protected $guarded = [];

    /**
     * 获取用户资产
     * @param $userId
     * @param $coinName
     * @return mixed
     * @throws \Exception
     */
    public static function getUserAssetsUserIdAndCoinName($userId,$coinName)
    {
        $assets = self::whereUid($userId)->whereCoinName($coinName)->first();
        if (empty($assets))
            throw new \Exception('资产异常');
        return $assets;
    }



}

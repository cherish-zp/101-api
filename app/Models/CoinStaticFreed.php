<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\CoinStaticFreed.
 *
 * @property string $id id
 * @property int $uid 用户id
 * @property float $freed_assets_num 释放资产数量
 * @property float $before_integral 之前积分
 * @property float $after_integral 之后积分
 * @property float $before_assets 之前资产
 * @property float $after_assets 之后资产
 * @property string $date 日期
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereAfterAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereAfterIntegral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereBeforeAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereBeforeIntegral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereFreedAssetsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinStaticFreed whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CoinStaticFreed extends Base
{
    use Uuids;
    protected $table = 'coin_static_freed';

    protected $guarded = [];

    /**
     * 静态释放 == 用户资产转化为积分
     * 登录时用当前的资产释放.
     *
     * @param $uid
     *
     * @throws \Exception
     */
    public static function staticFreed($uid)
    {
        $currentDate = date('Y-m-d', strtotime('-1 day'));

        $id = self::where(['uid'=>$uid, 'date'=>$currentDate])->value('id');
        if (!$id) {
            $assetsCoinName = SystemSetting::getFieldValue(SystemSetting::$assetsCoinName);
            $integralCoinName = SystemSetting::getFieldValue(SystemSetting::$integralCoinName);

            //获取用户资产
            $assets = UserAssets::getUserAssetsUserIdAndCoinName($uid, $assetsCoinName);
            //获取用户积分
            $integral = UserAssets::getUserAssetsUserIdAndCoinName($uid, $integralCoinName);
            //获取用户等级
            $userLevel = User::getLevel($uid);
            //获取释放比例
            $field = SystemSetting::$levelPrefix.$userLevel.SystemSetting::$releasePercentSuffix;
            $releasePercent = SystemSetting::getFieldValue($field);
            //用户将得到的积分
            $willGetFreedIntegral = bcmul($assets->available, $releasePercent);

            try {
                DB::beginTransaction();
                //更新用户积分
                $res = UserAssets::where(['uid'=>$uid, 'coin_name'=>$integralCoinName])->update([
                        'available'=> DB::raw('available+'.$willGetFreedIntegral),
                    ]);
                if (!$res) {
                    throw new \Exception('积分更新失败');
                }
                //更新用户资产
                $res = UserAssets::where(['uid'=>$uid, 'coin_name'=>$assetsCoinName])->update([
                        'available'=> DB::raw('available-'.$willGetFreedIntegral),
                    ]);
                if (!$res) {
                    throw new \Exception('资产更新失败');
                }
                //数据插入coin_static_freed 表
                $insertData = [
                        'uid'               => $uid,
                        'freed_assets_num'  => $willGetFreedIntegral,
                        'before_integral'   => $integral->available,
                        'after_integral'    => bcadd($integral, $willGetFreedIntegral),
                        'before_assets'     => $assets->available,
                        'after_assets'      => bcsub($assets->available, $willGetFreedIntegral),
                        'date'              => date('Y-m-d'),
                    ];
                $res = self::create($insertData);
                if (!$res) {
                    throw new \Exception('coin_static_freed create fail .. ');
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                logger(date('Y-m-d'."\t".$uid."\t".$e->getMessage()));
            }
        }
    }
}

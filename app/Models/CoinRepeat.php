<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\CoinRepeat
 *
 * @property int $id id
 * @property string $trade_no 交易单号
 * @property int $uid 用户id
 * @property float $integral 积分数量
 * @property float $assets 资产
 * @property int $status 1=已完成|2=待完成
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereIntegral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinRepeat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CoinRepeat extends Base
{
    use Uuids;

    public $table = 'coin_repeat';

    public $fillable = [
        'trade_no','uid','integral','assets','status'
    ];
    /**
     * @var int 已完成
     */
    public static $statusNo = 2;
    /**
     * @var int 待完成
     */
    public static $statusYes = 1;
    /**
     * @param $userId
     * @param $repeatIntegral 用户复投积分
     * @throws \Exception
     */
    public static function assetsReleaseToIntegral($userId,$repeatIntegral)
    {
        $queueCompleteAssetGain = SystemSetting::getFieldValue(SystemSetting::$queueCompleteAssetGainName);
        $assets = bcmul($repeatIntegral,$queueCompleteAssetGain);

        $insertData = [
            'trade_no'  =>  getOrderTradeOn(),
            'uid'       =>  $userId,
            'integral'  =>  $repeatIntegral,
            'assets'    =>  $assets,
            'status'    =>  self::$statusNo
        ];

        CoinRepeat::create($insertData);
    }
}

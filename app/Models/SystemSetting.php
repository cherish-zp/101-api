<?php

namespace App\Models;

use Illuminate\Support\Str;

/**
 * App\Models\SystemSetting.
 *
 * @property int $id ID
 * @property string $name 键
 * @property string $value 值
 * @property string|null $decription 描述
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereDecription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereValue($value)
 * @mixin \Eloquent
 */
class SystemSetting extends Base
{
    protected $table = 'system_setting';

    /**
     * @var string 等级参数前缀
     */
    public static $levelPrefix = 'level_';
    /**
     * @var string 积分等级猴嘴
     */
    public static $integralSuffix = '_integral';
    /**
     * @var string usdt等级后缀
     */
    public static $usdtSuffix = '_usdt';
    /**
     * @var string 释放层级后缀
     */
    public static $releaseLevelSuffix = '_release_level';
    /**
     * @var string 释放比例百分比后缀
     */
    public static $releasePercentSuffix = '_release_percent';

    public static $levelType = [1, 2, 3];

    public static $integralCoinName = 'integral_coin_name';
    /**
     * @var string 排队资产币种名称 usdt
     */
    public static $queueAssetCoinName = 'queue_assets_coin_name';
    /**
     * @var string 资产币种名称
     */
    public static $assetsCoinName = 'assets_coin_name';
    /**
     * @var string 复投积资产放大倍数
     */
    public static $queueCompleteAssetGain = 'queue_complete_asset_gain';
    /**
     * @var string 排队进场百分比
     */
    public static $queuedEntryPeoplePercent = 'queued_entry_people_percent';
    /**
     * @var string 系统账户名称
     */
    public static $systemAccountName = 'system_account';

    /**
     * @param $prefix
     * @param $middle
     * @param $suffix
     *
     * @return string
     */
    public static function getSystemField($prefix, $middle, $suffix)
    {
        return $prefix.$middle.$suffix;
    }

    /**
     * @param string $field 系统参数名称
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function getFieldValue(string $field)
    {
        $val = self::whereName($field)->value('value');
        if (empty($val)) {
            throw new \Exception($field.' no exists ');
        }
        if (Str::endsWith($field, 'coin_name')) {
            CtcCoin::getCoinInfo($val);
        }

        return $val;
    }
}

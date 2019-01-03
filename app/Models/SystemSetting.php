<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemSetting
 *
 * @property int $id ID
 * @property string $key 键
 * @property string $value 值
 * @property string|null $decription 描述
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereDecription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereValue($value)
 * @mixin \Eloquent
 * @property string $name 键
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemSetting whereName($value)
 */
class SystemSetting extends Base
{
    protected $table = 'system_setting';

    public static $levelPrefix = 'level';

    public static $levelType = [1,2,3];

    public static $integralCoinName = 'integral_coin_name';
    /**
     * @var string pei 排队资产币种名称
     */
    public static $queueAssetCoinName = 'queue_assets_coin_name';
    /**
     * @var string 资产币种名称
     */
    public static $assetsCoinName = 'assets_coin_name';
    /**
     * @var string 复投积资产放大倍数
     */
    public static $queueCompleteAssetGainName = 'queue_complete asset_ gain';



    /**
     * @param string $field
     * @return mixed
     * @throws \Exception
     */
    public static function getFieldValue(string $field)
    {
        $val =  self::whereName($field)->value('value');
        if (empty($val)) {
            throw new \Exception($field . 'no exists');
        }
        if (ends_with($field,'coin_name')) {
            CtcCoin::getCoinInfo($val);
        }
        return $val;
    }




}

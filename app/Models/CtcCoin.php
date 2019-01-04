<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CtcCoin
 *
 * @property int $id id
 * @property int $cid 币种id
 * @property string $name 币种简称
 * @property string $full_name 币种全称
 * @property string $icon 币种icon
 * @property int $isseller 卖方币种0=否1=是
 * @property int $transfer_into 允许转入0=禁止1=允许
 * @property float $transfer_into_fee 转入手续费
 * @property int $transfer_out 允许转出0=禁止1=允许
 * @property float $transfer_out_fee 转出手续费
 * @property string $account 充币账户
 * @property int $status 状态0=禁止1=启用
 * @property \Illuminate\Support\Carbon $created_at 添加时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @property string|null $deleted_at 删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereIsseller($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereTransferInto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereTransferIntoFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereTransferOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereTransferOutFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CtcCoin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CtcCoin extends Model
{
    public $table = 'ctc_coin';

    public static function getCoinInfo($coinName)
    {
        $coinInfo = self::whereName($coinName)->first();

        if (!$coinInfo)
            throw new \Exception('ctc_coin ' . $coinName . ' val  exception');

        return $coinInfo;
    }
}

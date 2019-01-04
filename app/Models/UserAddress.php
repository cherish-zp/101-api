<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\UserAddress
 *
 * @property int $id id
 * @property int $uid 用户id
 * @property int $cid 币种id
 * @property string $address 提币地址
 * @property string $remake 备注
 * @property int $default 默认1=是0=否
 * @property int $status 0=禁用1=可用
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereRemake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserAddress extends Base
{
    protected $table = 'user_address';
    protected $guarded = [];
    protected $hidden = [];


    public static function getAddressByUserIdCoinId($UserId,$cid)
    {
        $address = self::whereUserId($UserId)->whereCid($cid)->value('address');
        if (empty($address))
            throw new  \Exception('user address exception');
        return $address;
    }
}

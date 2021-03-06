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



    /**
     * @param $uid
     * 初始化用户的资产数据
     */
    public static function initUserAssets($uid)
    {
        //查询所有的币种
        $ctcCoins = CtcCoin::all(['cid','name']);
        $ctcCoinsArr = $ctcCoins->toArray();
        $ctcCoinsArrNew = [];
        foreach ($ctcCoinsArr as $key => $val) {
            $ctcCoinsArrNew[$val['cid']] = $val;
        }

        //查询用户资产已经产生的币种资产
        $userHasCoinObject = self::where(['uid'=>$uid])->get(['cid','coin_name']);
        $userHasCoinArray = $userHasCoinObject->toArray();
        if (count($ctcCoinsArr) == count($userHasCoinArray)) {
            return;
        }
        $userHasCoinArrayNew = [];
        foreach ($userHasCoinArray as $key => $val) {
            $userHasCoinArrayNew[$val['cid']] = $val;
        }

        $diff_arr = array_diff_key($ctcCoinsArrNew,$userHasCoinArrayNew);
        if (!empty($diff_arr)) {
            $insertUserAssetData = [];
            foreach ($diff_arr as $val) {
                array_push($insertUserAssetData,[
                    'id' => self::productUUid(),
                    'uid'=>$uid,
                    'cid'=>$val['cid'],
                    'coin_name'=>$val['name']
                ]);
            }
            self::insert($insertUserAssetData);
        }
    }

    /**
     * @return string
     * 生成用户的uuid
     */
    public static  function productUUid()
    {
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $uuid;
    }

    /**
     * 增加用户资产
     * @param $uid
     * @param $coinName
     * @param $num
     * @throws \Exception
     */
    public static function assetsAdd($uid,$coinName,$num)
    {
        $res = self::where(['uid'=>$uid,'coin_name'=>$coinName])->update([
            'available'=>\DB::raw('available+'.$num)
        ]);
        if (!$res) {
            throw new \Exception('user_assets + 操作失败');
        }
    }

    /**
     * 资产减少
     * @param $uid
     * @param $coinName
     * @param $num
     * @throws \Exception
     */
    public static function assetsReduce($uid,$coinName,$num)
    {
        $res = self::where(['uid'=>$uid,'coin_name'=>$coinName])->update([
            'available'=>\DB::raw('available-'.$num)
        ]);
        if (!$res) {
            throw new \Exception('user_assets - 操作失败');
        }
    }
}

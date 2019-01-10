<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Rank
 *
 * @property int $id id
 * @property int $uid 用户id
 * @property float $effect 业绩
 * @property float $assets 资产
 * @property int $rank 排名
 * @property int $rise 上升
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereEffect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rank extends Model
{
    use Uuids;
    protected $table = 'rank';
    protected $guarded = [];
    protected $hidden = ['uid'];

    /**
     * @param $uid
     * @param $effect
     * @param $assets
     * @return Rank|Model
     * @throws \Exception
     */
    public static function updateOrCreateData($uid,$effect,$assets)
    {
        $rank = self::updateOrCreate(
            ['uid'=>$uid],
            [
                'effect'=>\DB::raw('effect+'.$effect)
                ,'assets'=>$assets
            ]);
        if (!$rank)
            throw new \Exception('公排操作失败');
        return $rank;
    }
    
}

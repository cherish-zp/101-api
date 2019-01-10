<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 公排记录表
 * Class RankRecord
 *
 * @package App\Models
 * @property int $id id
 * @property int $uid 用户id
 * @property int $rank 当天排名
 * @property int $date 日期
 * @property float $effect 业绩
 * @property float $add_effect 新增业绩数量
 * @property float $queue_effect 排队业绩
 * @property float $repeat_effect 复投业绩
 * @property float $assets 资产
 * @property int $rand_rise 排名上升
 * @property string $resouce_id 来源表id
 * @property int $type 1:来源排队2.来源复投
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereAddEffect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereEffect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereQueueEffect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereRandRise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereRepeatEffect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereResouceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RankRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RankRecord extends Model
{
    protected $table = 'rank_record';
    protected $guarded = [];

    public function createData()
    {

    }
}

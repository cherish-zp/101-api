<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * 业绩记录模型
 * Class Effect
 *
 * @package App\Models
 * roperty int $id id
 * @property int $uid uid
 * @property string $resource_id 关联表id
 * @property int $num 业绩数量
 * @property int $type 1:来源排队2:来源复投
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $id id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Effect uuid($uuid, $first = true)
 */
class Effect extends Model
{
    use Uuids;
    protected $table = 'effect';
    protected $guarded = [];
    protected $hidden = ['uid'];

    const TYPE_QUEUE = 1;
    const TYPE_REPEAT = 2;

    /**
     * 添加业绩数据
     * @param $uid
     * @param $resourceId
     * @param $num
     * @param $type
     * @return Effect|Model
     * @throws \Exception
     */
    public static function createData($uid,$resourceId,$num,$type){
        $effect = self::create([
            'uid'=>$uid,
            'resource_id'=>$resourceId,
            'num'=>$num,
            'type'=>$type
        ]);
        if (!$effect)
            throw new \Exception('业绩数据产生失败!');
        return $effect;
    }

}

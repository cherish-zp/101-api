<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AppVersion
 *
 * @property int $id id
 * @property string $version 版本号
 * @property int $code 版本代码
 * @property string $downurl 下载地址
 * @property string $detail 更新内容
 * @property int $app_type 1=android2=ios
 * @property int $type 1=交易所|2=钱包
 * @property int $status 1=正常2=下线
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @property int $deleted 1=删除 0=不删除
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereAppType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereDownurl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereVersion($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at 删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppVersion whereDeletedAt($value)
 */
class AppVersion extends Model
{
    //
    protected $table='app_version';
}

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

    public static function getFieldValue(string $field)
    {
        return self::whereName($field)->value('value');
    }
}

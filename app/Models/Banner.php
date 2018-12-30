<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * App\Models\Banner
 *
 * @property int $id id
 * @property string $title 名称
 * @property string $image 图片
 * @property int $sort 排序
 * @property int $status 0=隐藏1=显示
 * @property int $deleted 1=已删除
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereTitle($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at 删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereDeletedAt($value)
 */
class Banner extends Base
{

    protected $table = 'app_banner';
    /**
     * @var int 未删除
     */
    public static $noDelete = 0;
    /**
     * @var int 隐藏
     */
    public static $showStatus = 1;

    public static function getBanners()
    {
        return self::whereDeleted(self::$noDelete)
            ->whereStatus(self::$showStatus)
            ->orderBy('sort','desc')
            ->get(['title','image','sort'])->map(function ($item,$key){
                return Config::get('constants.siteConfig.protocol')
                    . Config::get('constants.siteConfig.siteUrl')
                    . $item->image;
            });
    }

}

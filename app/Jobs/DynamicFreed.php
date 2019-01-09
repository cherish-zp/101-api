<?php

namespace App\Jobs;

use App\Models\CoinDynamicFreed;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use function PHPSTORM_META\type;

class DynamicFreed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * DynamicFreed constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param CoinDynamicFreed $coinDynamicFreed
     * @throws \Exception
     */
    public function handle(CoinDynamicFreed $coinDynamicFreed)
    {
        $user       = $this->user;

        $level      = $user->level;

        //获取用户可以获取到红包的层数
        $field      = SystemSetting::$levelPrefix . $level . SystemSetting::$releaseLevelSuffix;
        //根据等级获取用户可以释放的层级
        $levelFloor = SystemSetting::getFieldValue($field);
        //计算用户一共可以得到的层级
        if (!empty($user->lower_level_uids)) {
            $floorAll   = count(explode('|',$user->lower_level_uids)) + $levelFloor;
        } else {
            $floorAll = 0;
        }
        //获取系统配置最多的可获取的层级
        $maxLevel   = SystemSetting::getFieldValue(SystemSetting::$recommendGetMaxLevel);
        //如果大于 20 层 则 默认 获取最多的层级
        $floorAll   = $floorAll >= $maxLevel ?? $maxLevel;

        $freedData = [];

        for ($i= 1;$i<=$floorAll;$i++) {
            $freedData['level'.$i] = [];
            //获取本次等级的下所有用户
            //UserInvite::where(['uid'=>$user->id])->
        }

        $data  = [
            'freed_assets_num'=>1,
            'before_integral'=>11,
            'after_integral'=>1,
            'before_assets'=>1,
            'after_assets'=>1,
            'status'=>2,
        ];
        $where = [
            'uid'=> $user->uid,
            'date'=>date('Y-m-d'),
        ];
        $coinDynamicFreed::firstOrCreate($where,$data);
    }
}

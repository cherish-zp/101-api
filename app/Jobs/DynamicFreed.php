<?php

namespace App\Jobs;

use App\Models\CoinDynamicFreed;
use App\Models\User;
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
     */
    public function handle(CoinDynamicFreed $coinDynamicFreed)
    {
        $user = $this->user;


        $data  = [
            'freed_assets_num'=>   1,
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

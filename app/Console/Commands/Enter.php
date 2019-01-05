<?php

namespace App\Console\Commands;

use App\Models\Queue;
use App\Models\SystemSetting;
use App\Models\UserAssets;
use App\Models\UserFlow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Enter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '排队进场';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $percent = SystemSetting::getFieldValue(SystemSetting::$queuedEntryPeoplePercent);
        $count = Queue::getQueueingCount();
        $limit = ceil($count * $percent);

        if ($limit) {
            $queues = Queue::getQueueing($limit);
            foreach ($queues as $queue) {
                $this->updateQueueStatus($queue);
            }
        }
    }

    /**
     *处理进场资产逻辑
     * @param $queue
     * @throws \Exception
     */
    private function updateQueueStatus($queue)
    {

        DB::transaction(function () use ($queue) {
            Queue::whereId($queue->id)->update(['status' => Queue::$statusYes, 'enter_time' => date('Y-m-d H:i:s')]);
            $assetsName = SystemSetting::getFieldValue(SystemSetting::$assetsCoinName);
            $gain = SystemSetting::getFieldValue(SystemSetting::$queueCompleteAssetGain);

            $assets = UserAssets::getUserAssetsUserIdAndCoinName($queue->uid, $assetsName);
            $num = bcmul($queue->num, $gain);
            UserFlow::createFlow($queue->uid, '进场成功资产放大', $assets->available, bcadd($assets->available, $num),
                $num, $assets->cid, $assets->coin_name, $queue->trade_no, UserFlow::$queueStsus);
            UserAssets::whereCoinName($assetsName)->whereUid($queue->uid)->increment('available', $num);
        });
    }
}

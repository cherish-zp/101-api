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
            $queue = Queue::getQueueing($limit);
            foreach ($queue as $item) {
                echo $item->level;
                echo '<br>' . $item->num;
                $this->updateQueueStatus($item);
            }
        }
    }

    private function updateQueueStatus($item)
    {
        DB::beginTransaction();
        Queue::whereUuid($item->uuid)->update(['status' => Queue::$statusYes, 'enter_time' => date('Y-m-d H:i:s')]);
        $assetsName = SystemSetting::getFieldValue(SystemSetting::$assetsCoinName);
        $gain = SystemSetting::getFieldValue(SystemSetting::$queueCompleteAssetGain);


        UserFlow::createFlow($item->uid, $intoAccount, $outAccount, $title, $beforeNum, $afterNum, $num, $cid, $coinName, $resourceId, $type);
        UserAssets::whereCoinName($assetsName)->whereUid($item->uid)->increment('available', $item->num * $gain);
        DB::commit();
    }
}

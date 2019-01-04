<?php

namespace App\Console\Commands;

use App\Models\Queue;
use App\Models\SystemSetting;
use Illuminate\Console\Command;

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
        $percent = SystemSetting::getFieldValue('queued_entry_people_percent');
        $count = Queue::getQueueingCount();
        $limit = ceil($count * $percent);

        if ($limit) {
            $queue = Queue::getQueueing($limit);
            dd($queue);
        }
    }
}

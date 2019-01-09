<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DynamicFreed extends Command
{
    protected $signature = 'dynamic_freed';

    protected $description = '动态释放的信息存入表中';

    /**
     * Create a new command instace.
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
        $this->index();
    }

    public function index()
    {
        $count = User::where(['is_out'=>1,'status'=>1])->count();
        $this->info('开始任务  ' . $count);

        User::where(['is_out'=>1,'status'=>1])
            ->select(['uid','lower_level_uids','level'])->chunk(2 , function($user) use (&$count){
            foreach ($user as $item) {
                \App\Jobs\DynamicFreed::dispatch($item)->onQueue('dynamic_freed');
                $count --;
                $this->info('剩余: ' . $count);
            }
        });

        $this->info('执行完成');
    }
}

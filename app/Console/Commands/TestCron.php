<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\TestCronJob;
use Log;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cron working';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch(new TestCronJob());
    }
}

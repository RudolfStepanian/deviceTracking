<?php

namespace App\Console\Commands;

use App\Jobs\FindMeetsJob;
use App\Models\DeviceCloseContact;
use App\Services\DeviceTrackService;
use Illuminate\Console\Command;
use App\Models\DeviceCheckInOut;
use App\Models\DeviceMeetingTree;
use App\Models\DeviceMeetingConfig;
use Carbon\Carbon;
use Log;
use DB;
use phpDocumentor\Reflection\Types\Array_;

class DeviceTracking extends Command
{
    protected $signature = 'device:tracking';

    protected $description = 'tracking device';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DeviceTrackService $deviceTrackService)
    {
        $configuration = DeviceMeetingConfig::where('IsActive', 1)->first();

        if (!$configuration) {
            $this->info("Configuration not found.");
            return false;
        }

        $countOfFork = $configuration->CountOfFork;
        $count = DeviceMeetingTree::where('IsBuilded', 0)->count();

        if ($count) {
            $offset = ceil($count / $countOfFork);

            for($i = 0; $i < $offset; $i++) {
                dispatch(new FindMeetsJob($configuration, $i,$deviceTrackService));
            }
        }
        $this->info("Found $count tree(s) to process.");
    }
}

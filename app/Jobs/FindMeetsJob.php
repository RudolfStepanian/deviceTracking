<?php

namespace App\Jobs;

use App\Models\DeviceMeetingConfig;
use App\Models\DeviceMeetingTree;
use App\Services\DeviceTrackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FindMeetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $configuration;
    private $offset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($configuration, $offset, $deviceTrackService)
    {
        $this->configuration = $configuration;
        $this->offset = $offset;
        $this->handle($deviceTrackService);
    }

    public function handle(DeviceTrackService $deviceTrackService)
    {
        $trees = DeviceMeetingTree::where('IsBuilded', false)
                    ->limit($this->configuration->CountOfFork)
                    ->offset($this->configuration->CountOfFork * $this->offset)
                    ->get();

        foreach ($trees as $tree){
            $deviceTrackService->findMeetsByGEO($this->configuration, $tree);
            $deviceTrackService->findMeetsByCloseContacts($this->configuration, $tree);
            $deviceTrackService->findMeetsByCheckPoints($this->configuration, $tree);
            DeviceMeetingTree::find($tree->Id)->update(['IsBuilded' => true]);
        }
    }
}

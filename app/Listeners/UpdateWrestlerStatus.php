<?php

namespace App\Listeners;

use App\Events\WrestlerStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateWrestlerStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WrestlerStatusChanged  $event
     * @return void
     */
    public function handle(WrestlerStatusChanged $event)
    {
        if ($event->wrestler->status() == WrestlerStatus::RETIRED) {
            $event->wrestler->unretire();
        } else if ($event->wrestler->status() == WrestlerStatus::INJURED) {
            $event->wrestler->heal();
        } else if ($event->wrestler->status() == WrestlerStatus::SUSPENDED) {
            $event->wrestler->rejoin();
        }
    }
}

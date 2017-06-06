<?php

namespace App\Events;

use App\Models\Wrestler;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class WrestlerStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $wrestler;

    /**
     * Create a new event instance.
     *
     * @param Wrestler $wrestler
     */
    public function __construct(Wrestler $wrestler)
    {
        $this->wrestler = $wrestler;
    }
}

<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Exceptions\WrestlerCanNotBeSuspendedException;

trait HasSuspensions {

	abstract public function suspensions();

    public function hasSuspensions() {
        return $this->suspensions()->whereNull('ended_at')->count > 0;
    }

	public function suspend($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        $this->setStatusToSuspended();

        $this->suspensions()->create(['suspended_at' => $date]);

        return $this;
    }

    public function rejoin($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if (! $this->isSuspended())
        {
            throw new WrestlerCanNotBeSuspendedException;
        }

        $this->setStatusToActive();

        $this->suspensions()->whereNull('ended_at')->first()->rejoin($date);
    }
}
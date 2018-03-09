<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Exceptions\WrestlerNotSuspendedException;
use App\Exceptions\WrestlerAlreadySuspendedException;

trait HasSuspensions
{
    abstract public function suspensions();

    /**
     * Checks to see if the wrestler has past suspensions.
     *
     * @return bool
     */
    public function hasPastSuspensions()
    {
        return $this->pastSuspensions->isNotEmpty();
    }

    /**
     * Returns the wrestler's past suspensions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastSuspensions()
    {
        return $this->suspensions()->whereNotNull('ended_at');
    }

    public function currentSuspension()
    {
        return $this->suspensions()->whereNull('ended_at')->first();
    }

    /**
     * Checks to see if the wrestler is currently suspended.
     *
     * @return bool
     */
    public function isSuspended()
    {
        return $this->suspensions()->whereNull('ended_at')->count() > 0;
    }

    /**
     * Suspends the wrestler.
     *
     * @return ?
     */
    public function suspend()
    {
        if ($this->isSuspended()) {
            throw new WrestlerAlreadySuspendedException;
        }

        $this->setStatusToInactive();

        $this->suspensions()->create(['suspended_at' => Carbon::now()]);

        return $this;
    }

    public function unsuspend()
    {
        if (! $this->isSuspended()) {
            throw new WrestlerNotSuspendedException;
        }

        $this->setStatusToActive();

        $this->currentSuspension()->lift();

        return $this;
    }
}

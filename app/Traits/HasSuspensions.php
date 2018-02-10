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
     * @return boolean
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
        return $this->suspensions()->whereNotNull('ended_at')->get();
    }

    /**
     * Checks to see if the wrestler is currently suspended.
     *
     * @return boolean
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

        // dd($this);
        return $this;
    }

    public function renew()
    {
        if (! $this->isSuspended()) {
            throw new WrestlerNotSuspendedException;
        }

        $this->setStatusToActive();

        $this->suspensions()->whereNull('ended_at')->first()->renew();
    }
}

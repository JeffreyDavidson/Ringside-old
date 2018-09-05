<?php

namespace App\Traits;

use App\Models\Suspension;
use App\Exceptions\ModelAlreadySuspendedException;
use App\Exceptions\ModelNotSuspendedException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasSuspensions
{
    /**
     * A wrestler can have many suspensions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suspensions()
    {
        return $this->morphMany(Suspension::class, 'suspendee');
    }

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

    /**
     * Returns the wrestler's current suspensions.
     *
     * @return \App\Models\Suspension
     */
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
        return $this->suspensions()->whereNull('ended_at')->exists();
    }

    /**
     * Scope a query to only include wrestlers that are currently suspended.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuspended(Builder $query)
    {
        $query->whereHas('suspensions', function ($query) {
            $query->whereNull('ended_at');
        });
    }

    /**
     * Suspend a wrestler.
     *
     * @return $this
     */
    public function suspend()
    {
        if ($this->isSuspended()) {
            throw new ModelAlreadySuspendedException;
        }

        $this->deactivate();

        $this->suspensions()->create(['suspended_at' => Carbon::now()]);

        return $this;
    }

    /**
     * Unsuspend a wrestler.
     *
     * @return $this
     */
    public function unsuspend()
    {
        if (!$this->isSuspended()) {
            throw new ModelNotSuspendedException;
        }

        $this->activate();

        $this->currentSuspension()->lift();

        return $this;
    }
}

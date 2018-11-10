<?php

namespace App\Traits;

use App\Models\Suspension;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\ModelIsActiveException;
use App\Exceptions\ModelIsSuspendedException;

trait HasSuspensions
{
    /**
     * A model can have many suspensions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function suspensions()
    {
        return $this->morphMany(Suspension::class, 'suspendee');
    }

    /**
     * Checks to see if the model has past suspensions.
     *
     * @return bool
     */
    public function hasPastSuspensions()
    {
        return $this->pastSuspensions()->exists();
    }

    /**
     * Returns the wrestler's past suspensions.
     *
     * @return \Illuminate\Database\Query\Builder
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
     * Suspends a wrestler.
     *
     * @return $this
     *
     * @throws \App\Exceptions\ModelIsSuspendedException
     */
    public function suspend()
    {
        if ($this->isSuspended()) {
            throw new ModelIsSuspendedException;
        }

        $this->deactivate();

        $this->suspensions()->create(['suspended_at' => now()]);

        return $this;
    }

    /**
     * Reinstate a suspended wrestler.
     *
     * @return $this
     *
     * @throws \App\Exceptions\ModelIsActiveException
     */
    public function reinstate()
    {
        if ($this->isActive()) {
            throw new ModelIsActiveException;
        }

        $this->activate();

        $this->currentSuspension()->lift();

        return $this;
    }
}

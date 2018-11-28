<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Retirement;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\ModelIsActiveException;
use App\Exceptions\ModelIsRetiredException;

/**
 * @mixin \Eloquent
 */
trait HasRetirements
{
    /**
     * A wrestler can have many retirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retirable');
    }

    /**
     * Checks to see if the most has been retired before.
     *
     * @return bool
     */
    public function hasPastRetirements()
    {
        return $this->pastRetirements->isNotEmpty();
    }

    /**
     * Retrieves the model's past retirements.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function pastRetirements()
    {
        return $this->retirements()->whereNotNull('ended_at');
    }

    /**
     * Retrieves the model's current retirement.
     *
     * @return \App\Models\Retirement
     */
    public function currentRetirement()
    {
        return $this->retirements()->whereNull('ended_at')->first();
    }

    /**
     * Checks to see if the model is retired.
     *
     * @return bool
     */
    public function isRetired()
    {
        return $this->retirements()->whereNull('ended_at')->exists();
    }

    /**
     * Scope a query to only include models that are currently retired.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired(Builder $query)
    {
        return $query->whereHas('retirements', function ($query) {
            $query->whereNull('ended_at');
        });
    }

    /**
     * Retires the model.
     *
     * @return $this
     *
     * @throws \App\Exceptions\ModelIsRetiredException
     */
    public function retire()
    {
        if ($this->isRetired()) {
            throw new ModelIsRetiredException;
        }

        if ($this->isActive()) {
            $this->deactivate();
        }

        $this->retirements()->create(['retired_at' => Carbon::now()]);

        return $this;
    }

    /**
     * Uretires the model.
     *
     * @return $this
     *
     * @throws \App\Exceptions\ModelIsActiveException
     */
    public function unretire()
    {
        if ($this->isActive()) {
            throw new ModelIsActiveException;
        }

        $this->activate();

        $this->currentRetirement()->end();

        return $this;
    }
}

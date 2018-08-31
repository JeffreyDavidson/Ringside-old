<?php

namespace App\Traits;

use App\Models\Retirement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\ModelNotRetiredException;
use App\Exceptions\ModelAlreadyRetiredException;

trait HasRetirements
{
    /**
     * A wrestler can have many retirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retiree');
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
     * @return \Illuminate\Database\Eloquent\Collection
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
     */
    public function retire()
    {
        if ($this->isRetired()) {
            throw new ModelAlreadyRetiredException;
        }

        $this->deactivate();

        $this->retirements()->create(['retired_at' => Carbon::now()]);

        return $this;
    }

    /**
     * Uretires the model.
     *
     * @return $this
     */
    public function unretire()
    {
        if (!$this->isRetired()) {
            throw new ModelNotRetiredException;
        }

        $this->activate();

        $this->currentRetirement()->end();

        return $this;
    }
}

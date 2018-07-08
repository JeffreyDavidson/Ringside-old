<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\ModelNotRetiredException;
use App\Exceptions\ModelAlreadyRetiredException;

trait HasRetirements
{
    abstract public function retirements();

    /**
     * Checks to see if the most has been retired before.
     *
     * @return App\Models\Retirement|null
     */
    public function hasPastRetirements()
    {
        return $this->pastRetirements->isNotEmpty();
    }

    /**
     * Retrieves the model's past retirements.
     *
     * @return App\Models\Retirement|null
     */
    public function pastRetirements()
    {
        return $this->retirements()->whereNotNull('ended_at');
    }

    /**
     * Retrieves the model's current retirement.
     *
     * @return App\Models\Retirement|null
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired(Builder $query)
    {
        return $query->whereHas('retirements', function ($q) {
            $q->whereNull('ended_at');
        });
    }

    /**
     * Retires the model.
     *
     * @return
     */
    public function retire()
    {
        if ($this->isRetired()) {
            throw new ModelAlreadyRetiredException;
        }

        $this->inactivate();

        $this->retirements()->create(['retired_at' => Carbon::now()]);

        return $this;
    }

    /**
     * Uretires the model.
     *
     * @return App\Models\Retirement|null
     */
    public function unretire()
    {
        if (! $this->isRetired()) {
            throw new ModelNotRetiredException;
        }

        $this->activate();

        $this->currentRetirement()->end();

        return $this;
    }
}

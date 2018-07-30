<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Exceptions\WrestlerNotInjuredException;
use App\Exceptions\WrestlerAlreadyInjuredException;

trait HasInjuries
{
    /** @abstract */
    abstract public function injuries();

    /**
     * Checks to see if the wrestler has past injuries.
     *
     * @return bool
     */
    public function hasPastInjuries()
    {
        return $this->pastInjuries->isNotEmpty();
    }

    /**
     * Returns all the past injuries for a wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastInjuries()
    {
        return $this->injuries()->whereNotNull('healed_at');
    }

    /**
     * Returns all the current injuries for a wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function currentInjury()
    {
        return $this->injuries()->whereNull('healed_at')->first();
    }

    /**
     * Checks to see if the wrestler is currently injured.
     *
     * @return bool
     */
    public function isInjured()
    {
        return $this->injuries()->whereNull('healed_at')->exists();
    }

    /**
     * Scope a query to only include wrestlers that are currently injured.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInjured($query)
    {
        return $query->whereHas('injuries', function ($query) {
            $query->whereNull('healed_at');
        });
    }

    /**
     * Injure a wrestler.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function injure()
    {
        if ($this->isInjured()) {
            throw new WrestlerAlreadyInjuredException;
        }

        $this->deactivate();

        $this->injuries()->create(['injured_at' => Carbon::now()]);

        return $this;
    }

    /**
     * Recover a wrestler from an injury.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function recover()
    {
        if (! $this->isInjured()) {
            throw new WrestlerNotInjuredException;
        }

        $this->activate();

        $this->currentInjury()->heal();

        return $this;
    }
}

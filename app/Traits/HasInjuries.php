<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Exceptions\WrestlerNotInjuredException;
use App\Exceptions\WrestlerAlreadyInjuredException;

trait HasInjuries
{
    /**
     * Make a new related instance for the given model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInjured($query)
    {
        return $query->isInjured();
    }

    public function injure()
    {
        if ($this->isInjured()) {
            throw new WrestlerAlreadyInjuredException;
        }

        $this->deactivate();

        $this->injuries()->create(['injured_at' => Carbon::now()]);

        return $this;
    }

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

<?php

namespace App\Traits;

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
     * @return boolean
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
        return $this->injuries()->whereNotNull('healed_at')->get();
    }

    /**
     * Checks to see if the wrestler is currently injured.
     *
     * @return boolean
     */
    public function isInjured()
    {
        return $this->injuries()->whereNull('healed_at')->count() > 0;
    }

    public function injure($injuredAt = null)
    {
        if ($this->isInjured()) {
            throw new WrestlerAlreadyInjuredException;
        }

        $this->setStatusToInactive();

        $this->injuries()->create(['injured_at' => $injuredAt ?: $this->freshTimestamp()]);
    }

    public function heal($healedAt = null)
    {
        if (! $this->isInjured()) {
            throw new WrestlerNotInjuredException;
        }

        $this->setStatusToActive();

        $this->injuries()->whereNull('healed_at')->first()->heal($healedAt);

        return $this;
    }
}

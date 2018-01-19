<?php

namespace App\Traits;

use App\Exceptions\WrestlerNotInjuredException;
use App\Exceptions\WrestlerAlreadyInjuredException;

trait HasInjuries
{
    abstract public function injuries();

    public function hasPastInjuries()
    {
        return $this->pastInjuries->isNotEmpty();
    }

    public function pastInjuries()
    {
        return $this->injuries()->whereNotNull('healed_at');
    }

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

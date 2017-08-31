<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Exceptions\WrestlerNotInjuredException;
use App\Exceptions\WrestlerAlreadyInjuredException;

trait HasInjuries
{
    abstract public function injuries();

    public function hasPreviousInjuries()
    {
        return $this->previousInjuries->isNotEmpty();
    }

    public function previousInjuries()
    {
        return $this->injuries()->whereNotNull('healed_at');
    }

    public function isInjured()
    {
        return $this->injuries()->whereNull('healed_at')->count() > 0;
    }

    public function injure($date = null)
    {
        if ($this->isInjured()) {
            throw new WrestlerAlreadyInjuredException;
        }

        $this->setStatusToInactive();

        $this->injuries()->create(['injured_at' => $date ?: Carbon::now()]);

        return $this;
    }

    public function heal($date = null)
    {
        if (! $this->isInjured()) {
            throw new WrestlerNotInjuredException;
        }

        $this->setStatusToActive();

        $this->injuries()->whereNull('healed_at')->first()->healed($date ?: Carbon::now());

        return $this;
    }
}
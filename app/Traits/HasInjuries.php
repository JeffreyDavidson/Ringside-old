<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Exceptions\WrestlerCanNotBeHealedException;
use App\Exceptions\WrestlerCanNotBeInjuredException;

trait HasInjuries {

	abstract public function retirements();

    public function hasInjuries() {
        return $this->injuries()->whereNull('healed_at')->count > 0;
    }

    public function injure($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if (! $this->isActive()) {
            throw new WrestlerCanNotBeInjuredException;
        }

        $this->setStatusToInjured();

        $this->injuries()->create(['injured_at' => $date]);

        return $this;
    }

    public function heal($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if (! $this->isInjured())
        {
            throw new WrestlerCanNotBeHealedException;
        }

        $this->setStatusToActive();

        $this->injuries()->whereNull('healed_at')->first()->healed($date);
    }
}
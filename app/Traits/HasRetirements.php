<?php

namespace App\Traits;

use App\Exceptions\WrestlerNotRetiredException;
use Carbon\Carbon;
use App\Exceptions\WrestlerAlreadyRetiredException;

trait HasRetirements
{
    abstract public function retirements();

    public function hasPastRetirements()
    {
        return $this->pastRetirements->isNotEmpty();
    }

    public function pastRetirements()
    {
        return $this->retirements()->whereNotNull('ended_at');
    }

    public function isRetired()
    {
        return $this->retirements()->whereNull('ended_at')->count() > 0;
    }

    public function retire()
    {
        if ($this->isRetired()) {
            throw new WrestlerAlreadyRetiredException;
        }

        $this->setStatusToInactive();

        $this->retirements()->create(['retired_at' => Carbon::now()]);

        return $this;
    }

    public function unretire()
    {
        if (!$this->isRetired()) {
            throw new WrestlerNotRetiredException;
        }

        $this->setStatusToActive();

        $this->retirements()->whereNull('ended_at')->first()->unretire();
    }
}

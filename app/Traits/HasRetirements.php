<?php

namespace App\Traits;

use App\Exceptions\WrestlerNotRetiredException;
use Carbon\Carbon;
use App\Exceptions\WrestlerAlreadyRetiredException;

trait HasRetirements {

	abstract public function retirements();

    public function hasPreviousRetirements()
    {
        return $this->previousRetirements->isNotEmpty();
    }

    public function previousRetirements()
    {
        return $this->retirements()->whereNotNull('ended_at');
    }

    public function isRetired()
    {
        return $this->retirements()->whereNull('ended_at')->count() > 0;
    }

	public function retire($date = null)
    {
        if ($this->isRetired())
        {
            throw new WrestlerAlreadyRetiredException;
        }

        $this->setStatusToInactive();

        $this->retirements()->create(['retired_at' => $date ?: Carbon::now()]);

        return $this;
    }

    public function unretire($date = null)
    {
        if (! $this->isRetired())
        {
            throw new WrestlerNotRetiredException;
        }

        $this->setStatusToActive();

        $this->retirements()->whereNull('ended_at')->first()->unretire($date ?: Carbon::now());
    }
}
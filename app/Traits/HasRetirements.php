<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Exceptions\WrestlerCanNotRetireException;

trait HasRetirements {

	abstract public function retirements();

    public function hasRetirements() {
        return $this->retirements()->whereNull('ended_at')->count > 0;
    }

	public function retire($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        $this->setStatusToRetired();

        $this->retirements()->create(['retired_at' => $date]);

        return $this;
    }

    public function unretire($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if (! $this->isRetired())
        {
            throw new WrestlerCanNotRetireException;
        }

        $this->setStatusToActive();

        $this->retirements()->whereNull('ended_at')->first()->unretire($date);
    }
}
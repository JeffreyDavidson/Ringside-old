<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Retirement;
use App\Exceptions\WrestlerNotRetiredException;
use App\Exceptions\WrestlerAlreadyRetiredException;

trait HasRetirements
{
    public function isRetired()
    {
        return is_null($this->retired_at);
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
        if (! $this->isRetired()) {
            throw new WrestlerNotRetiredException;
        }

        $this->setStatusToActive();

        $this->currentRetirement()->end();

        return $this;
    }
}

<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Retirement;
use App\Exceptions\WrestlerNotRetiredException;
use App\Exceptions\WrestlerAlreadyRetiredException;

trait HasRetirements
{
    /**
     * A wrestler can have many retirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retireable');
    }

    public function hasPastRetirements()
    {
        return $this->pastRetirements->isNotEmpty();
    }

    public function pastRetirements()
    {
        return $this->retirements()->whereNotNull('ended_at');
    }

    public function currentRetirement()
    {
        return $this->retirements()->whereNull('ended_at')->first();
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
        if (! $this->isRetired()) {
            throw new WrestlerNotRetiredException;
        }

        $this->setStatusToActive();

        $this->currentRetirement()->end();

        return $this;
    }
}

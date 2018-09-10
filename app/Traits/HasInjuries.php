<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Injury;
use App\Exceptions\ModelIsActiveException;
use App\Exceptions\ModelIsInjuredException;

trait HasInjuries
{
    /**
     * A wrestler can have many injuries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function injuries()
    {
        return $this->hasMany(Injury::class);
    }

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

    /**
     * Returns all the current injuries for a wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInjured($query)
    {
        return $query->whereHas('injuries', function ($query) {
            $query->whereNull('healed_at');
        });
    }

    /**
     * Injure a wrestler.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws App\Exceptions\ModelIsInjuredException
     */
    public function injure()
    {
        if ($this->isInjured()) {
            throw new ModelIsInjuredException;
        }

        $this->deactivate();

        $this->injuries()->create(['injured_at' => Carbon::now()]);

        return $this;
    }

    /**
     * Recover a wrestler from an injury.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws App\Exceptions\ModelIsActiveException
     */
    public function recover()
    {
        if ($this->is_active) {
            throw new ModelIsActiveException;
        }

        $this->activate();

        $this->currentInjury()->heal();

        return $this;
    }
}

<?php

namespace App\Traits;

use App\Exceptions\ModelIsActiveException;
use App\Exceptions\ModelIsInactiveException;
use App\Exceptions\ModelNotHiredException;
use Illuminate\Database\Eloquent\Builder;

trait HasStatus
{
    /**
     * Scope a query to only include models that are currently active.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include models that are currently inactive.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query)
    {
        $query->where('is_active', false);

        if ($this->retirements) {
            $query->whereDoesntHave('retirements', function ($query) {
                $query->whereNull('ended_at');
            });
        }

        if ($this->injuries) {
            $query->whereDoesntHave('injuries', function ($query) {
                $query->whereNull('healed_at');
            });
        }

        if ($this->suspensions) {
            $query->whereDoesntHave('suspensions', function ($query) {
                $query->whereNull('ended_at');
            });
        }
    }

    /**
     * Activates an inactive model.
     *
     * @return bool
     *
     * @throws App\Exceptions\ModelIsActiveExcepton
     */
    public function activate()
    {
        if ($this->isActive()) {
            throw new ModelIsActiveException;
        }

        if (($this instanceof Wrestler || $this instanceof Manager) && $this->hired_at->gt(today())) {
            throw new ModelNotHiredException;
        } elseif ($this instanceof Title && $this->introduced_at->gt(today())) {
            throw new TitleNotIntroduced;
        }

        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivates an active model.
     *
     * @return bool
     *
     * @throws App\Exceptions\ModelIsInactiveExcepton
     */
    public function deactivate()
    {
        if (!$this->isActive()) {
            throw new ModelIsInactiveException;
        }

        return $this->update(['is_active' => false]);
    }

    /**
     * Checks to see if the model is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active;
    }
}

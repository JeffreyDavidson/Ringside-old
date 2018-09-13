<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\ModelIsActiveException;
use App\Exceptions\ModelIsInactiveException;

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

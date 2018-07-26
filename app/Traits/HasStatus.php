<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasStatus
{
    /**
     * Scope a query to only include models that are currently active.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include models that are currently inactive.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
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
     */
    public function activate()
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Inactives an active model.
     *
     * @return bool
     */
    public function deactivate()
    {
        return $this->update(['is_active' => false]);
    }
}

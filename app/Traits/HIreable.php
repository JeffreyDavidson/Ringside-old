<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Hireable
{
    public static function bootHireable()
    {
        static::creating(function (Model $model) {
            $model->is_active = $model->hired_at->lte(today());
        });

        static::saving(function (Model $model) {
            if ($model->isActive()) {
                $model->is_active = $model->hired_at->lte(today());
            }
        });
    }

    /**
     * Scope a query to only include models that are hired before a specific date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHiredBefore(Builder $query, $date)
    {
        return $query->where('hired_at', '<', $date);
    }
}

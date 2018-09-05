<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Hireable
{
    public static function bootHireable()
    {
        static::creating(function (Model $model) {
            $model->is_active = $model->hired_at->lte(Carbon::today());
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

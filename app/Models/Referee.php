<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends Model
{
    use Presentable, SoftDeletes;

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\RefereePresenter';

    /**
     * Scope a query to only include referees hired before a specific date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHiredBefore($query, $date)
    {
        return $query->where('hired_at', '<', $date);
    }
}

<?php

namespace App\Models;

use App\Traits\HasStatus;
use App\Traits\HasTitles;
use App\Traits\HasMatches;
use App\Traits\HasInjuries;
use App\Traits\HasManagers;
use App\Traits\HasRetirements;
use App\Traits\HasSuspensions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wrestler extends Model
{
    use HasInjuries, HasManagers, HasMatches, HasRetirements, HasStatus, HasSuspensions, HasTitles, Presentable, SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hired_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'hired_at', 'hometown', 'height', 'weight', 'signature_move'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\WrestlerPresenter';

    /**
     * Scope a query to only include wrestlers hired before a specific date.
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

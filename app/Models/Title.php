<?php

namespace App\Models;

use App\Traits\HasStatus;
use App\Traits\HasMatches;
use App\Traits\HasRetirements;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Title extends Model
{
    use HasMatches, HasRetirements, HasStatus, Presentable, SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'introduced_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'introduced_at'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\TitlePresenter';

    /**
     * A title can have many champions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champions()
    {
        return $this->hasMany(Championship::class);
    }

    /**
     * A title can be added to many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }

    /**
     * A title can have many retirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retiree');
    }

    /**
     * Returns the current champion for the title.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \App\Models\Wrestler
     */
    public function currentChampion()
    {
        return $this->champions()->whereNull('lost_on')->toHasOne();
    }

    /**
     * Checks to see if the title currently has a champion.
     *
     * @return bool
     */
    public function hasAChampion()
    {
        return $this->champions()->whereNull('lost_on')->exists();
    }
}

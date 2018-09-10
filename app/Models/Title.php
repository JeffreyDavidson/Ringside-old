<?php

namespace App\Models;

use App\Traits\HasStatus;
use App\Traits\HasMatches;
use App\Traits\HasRetirements;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use HasStatus,
        HasMatches,
        HasRetirements,
        Presentable,
        SoftDeletes;

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
    protected $fillable = ['name', 'slug', 'is_active', 'introduced_at'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\TitlePresenter';

    /**
     * A title can have many champions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function champions()
    {
        return $this->belongsToMany(Wrestler::class, 'championships')->using(Championship::class)->withPivot('id', 'won_on', 'lost_on', 'successful_defenses');
    }

    /**
     * Defines the relationship between a title and its current champion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentChampion()
    {
        return $this->champions()->wherePivot('lost_on', null)->limit(1);
    }

    /**
     * Defines the relationship between a title and its previous champion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function previousChampion()
    {
        return $this->champions()->wherePivot('lost_on', '!=', null)->latest('championships.won_on')->limit(1);
    }

    /**
     * Retrieves the current champion for the title.
     *
     * @return \App\Models\Wrestler|null
     */
    public function getCurrentChampionAttribute()
    {
        return $this->currentChampion()->first();
    }

    /**
     * Retrieves the current champion for the title.
     *
     * @return \App\Models\Wrestler|null
     */
    public function getPreviousChampionAttribute()
    {
        return $this->previousChampion()->first();
    }

    public function isVacant()
    {
        return blank($this->currentChampion);
    }
}

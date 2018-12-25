<?php

namespace App\Models;

use App\Traits\Retirable;
use App\Traits\Statusable;
use App\Models\Championship;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ModelIsActiveException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\TitleNotIntroducedException;

class Title extends Model
{
    use Statusable, Retirable, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'introduced_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'is_active', 'introduced_at'];

    /**
     * A title can have many champions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function championships()
    {
        return $this->hasMany(Championship::class);
    }

    /**
     * Defines the relationship between a title and its current champion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getCurrentChampionAttribute()
    {
        return $this->championships()->whereNull('lost_on')->first()->champion;
    }

    /**
     * Defines the relationship between a title and its previous champion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getPreviousChampionAttribute()
    { 
        return $this->championships()->whereNotNull('lost_on')->latest('championships.won_on')->first()->champion;
    }

    /**
     * Checks to see if the title is currently held.
     *
     * @return bool
     */
    public function isVacant()
    {
        return empty($this->currentChampion);
    }

    /**
     * Activates a title.
     *
     * @return bool
     *
     * @throws \App\Exceptions\ModelIsActiveException
     * @throws \App\Exceptions\TitleNotIntroducedException
     */
    public function activate()
    {
        if ($this->isActive()) {
            throw new ModelIsActiveException;
        } elseif ($this->introduced_at->gt(today())) {
            throw new TitleNotIntroducedException;
        }

        return $this->update(['is_active' => true]);
    }

    /**
     * Formats introduced at date for model.
     *
     * @return string
     */
    public function getFormattedIntroducedAtDateAttribute()
    {
        return $this->introduced_at->format('F j, Y');
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use SoftDeletes;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['introduced_at', 'retired_at'];

    /**
<<<<<<< Updated upstream:app/Title.php
     * A title can have many champions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
=======
     * @return mixed
>>>>>>> Stashed changes:app/Models/Title.php
     */
    public function champions()
    {
        return $this->hasMany(TitleHistory::class)->orderBy('won_on');
    }

    /**
     * A title can be added to many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function matches()
    {
        return $this->belongsToMany(Match::class)->with('event');
    }

    /**
     * Retrieve valid titles to be competed for.
     *
     * @param Builder $query
     * @param $date
     */
    public function scopeValid($query, $date)
    {
        return $query->where('introduced_at', '<=', $date->toDateString())->where(function($query) use ($date) {
			$query->whereNull('retired_at')->orWhere('retired_at', '>', $date->toDateString());
		});
    }

    /**
     * Crown the new champion.
     *
     * @param $wrestler
     * @param $date
     */
    public function setNewChampion($wrestler, $date = null)
	{
		if(! $date) {
			$date = Carbon::now();
		}

    	if($formerChampion = $this->getCurrentChampion()) {
			$formerChampion->loseTitle($this, $date);
		}

		$wrestler->winTitle($this, $date);
    }

    /**
     * Get the formatted introduced at field.
     *
     * @return date
     */
    public function getFormattedIntroducedAtAttribute()
    {
        return $this->introduced_at->format('F j, Y');
    }

    /**
     * Get the formatted retired at field.
     *
     * @return date
     */
    public function getFormattedRetiredAtAttribute()
    {
        return $this->retired_at->format('F j, Y');
    }

    /**
     * Get the longest title reign held by a wrestler.
     *
     * @return Wrestler $wrestler
     */
    public function getLongestTitleReignAttribute()
    {
        return 'longest title reign';
    }

    /**
     * Get the wrestler who has defended the title the most times.
     *
     * @return Wrestler $wrestler
     */
    public function getMostTitleDefensesAttribute()
    {
        return 'most title defenses';
    }

    /**
     * Get the wrestler who has held the title the most times.
     *
     * @return Wrestler $wrestler
     */
    public function getMostTitleReignsAttribute()
    {
        return 'most title reigns';
    }

    /**
     * Set the introduced at field for the title.
     *
     * @return date
     */
    public function setIntroducedAtAttribute($date)
    {
        if($date instanceof Carbon) {
            return $this->attributes['introduced_at'] = $date;
        }

        return $this->attributes['introduced_at'] = Carbon::parse($date);
    }

    /**
     * Get the current champion for the title.
     *
     * @return Wrestler $wrestler
     */
	public function getCurrentChampion() {
		return $this->champions()->whereNull('lost_on')->first() ? $this->champions()->whereNull('lost_on')->first()->wrestler : null;
    }
}

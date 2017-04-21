<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['introduced_at', 'retired_at'];

    public function champions()
    {
        return $this->hasMany(TitleHistory::class)->orderBy('won_on');
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class)->with('event');
    }

    public function scopeValid($query, $date)
    {
        return $query->where('introduced_at', '<=', $date->toDateString())->where(function($query) use ($date) {
			$query->whereNull('retired_at')->orWhere('retired_at', '>', $date->toDateString());
		});
    }

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

    public function getFormattedIntroducedAtAttribute()
    {
        return $this->introduced_at->format('F j, Y');
    }

    public function getFormattedRetiredAtAttribute()
    {
        return $this->retired_at->format('F j, Y');
    }

    public function getLongestTitleReignAttribute()
    {
        return 'longest title reign';
    }

    public function getMostTitleDefensesAttribute()
    {
        return 'most title defenses';
    }

    public function getMostTitleReignsAttribute()
    {
        return 'most title reigns';
    }

    public function setIntroducedAtAttribute($date)
    {
        if($date instanceof \Carbon\Carbon) {
            return $this->attributes['introduced_at'] = $date;
        }

        return $this->attributes['introduced_at'] = Carbon::createFromFormat('m/d/Y', $date);
    }

	public function getCurrentChampion() {
		return $this->champions()->whereNull('lost_on')->first() ? $this->champions()->whereNull('lost_on')->first()->wrestler : null;
    }
}

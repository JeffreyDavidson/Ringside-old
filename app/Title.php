<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;

class Title extends Model
{
    protected $dates = ['introduced_at', 'retired_at'];

    public function champions()
    {
        return $this->hasMany(TitleHistory::class);
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class)->with('event');
    }

    public function scopeIntroducedBefore($query, $date)
    {
        return $query->where('introduced_at', '<=', $date);
    }

    public function setNewChampion($wrestler)
    {
    	if($formerChampion = $this->getCurrentChampion()) {
			$formerChampion->wrestler->loseTitle($this);
		}

		$wrestler->winTitle($this);
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

	public function getCurrentChampion() {
    	Log::info($this->champions()->whereNull('lost_on')->first());
		return $this->champions()->whereNull('lost_on')->first();
    }
}

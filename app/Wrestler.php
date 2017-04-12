<?php

namespace App;

use App\Traits\HasManagers;
use App\Traits\HasStatuses;
use App\Traits\HasTitles;
use App\Traits\HasRetirements;
use App\Traits\HasInjuries;
use Illuminate\Database\Eloquent\Model;

class Wrestler extends Model
{
	use HasStatuses, HasManagers, HasTitles, HasRetirements, HasInjuries;

    protected $guarded = [];

    protected $dates = ['hired_at'];

    public function bio()
    {
        return $this->hasOne(WrestlerBio::class);
    }

    public function managers()
    {
        return $this->belongsToMany(Manager::class)->withPivot('hired_on', 'fired_on')->withTimestamps();
    }

    public function titles()
    {
        return $this->hasMany(TitleHistory::class);
    }

	public function matches()
	{
		return $this->belongsToMany(Match::class);
	}

	public function injuries()
	{
		return $this->hasMany(WrestlerInjury::class);
	}

	public function retirements()
	{
		return $this->hasMany(WrestlerRetirement::class);
	}

	public function status() {
        return $this->getAttribute('status_id');
    }
}

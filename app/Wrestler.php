<?php

namespace App;

use App\Traits\HasManagers;
use App\Traits\HasStatuses;
use App\Traits\HasTitles;
use Carbon\Carbon;
use App\Exceptions\WrestlerCanNotBeHealedException;
use Illuminate\Database\Eloquent\Model;

class Wrestler extends Model
{
	use HasStatuses, HasManagers, HasTitles;

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
        return $this->belongsToMany(TitleHistory::class);
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
		return $this->status_id;
    }

    public function injure($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        $this->setStatusToInjured();

        $this->injuries()->create(['injured_at' => $date]);

        return $this;
    }

    public function heal($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if (! $this->isInjured())
        {
            throw new WrestlerCanNotBeHealedException;
        }

        $this->setStatusToActive();

        $this->injuries()->whereNull('healed_at')->first()->healed($date);
    }

    public function retire($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        $this->setStatusToRetired();

        $this->retirements()->create(['retired_at' => $date]);

        return $this;
    }

    public function unretire($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if ($this->isRetired())
        {
            $this->retirements()->update(['ended_at' => Carbon::now()]);
        }

        if (! $this->isRetired())
        {
            throw new WrestlerCanNotRetireException;
        }

        $this->setStatusToActive();

        $this->retirements()->whereNull('ended_at')->first()->unretire($date);
    }

    public function hasInjuries() {
        return $this->injuries()->whereNULL('healed_at')->count > 0;
    }

    public function hasRetirements() {
        return $this->retirements()->whereNULL('ended_at')->count > 0;
    }
}

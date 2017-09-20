<?php

namespace App\Models;

use App\Traits\HasManagers;
use App\Traits\HasStatuses;
use App\Traits\HasTitles;
use App\Traits\HasRetirements;
use App\Traits\HasSuspensions;
use App\Traits\HasInjuries;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracodes\Presenter\Traits\Presentable;

class Wrestler extends Model
{
	use HasStatuses, HasManagers, HasTitles, HasRetirements, HasSuspensions, HasInjuries, SoftDeletes, Presentable;

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\WrestlerPresenter';

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
    protected $dates = ['hired_at'];

    /**
     * A wrestler can have many managers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(Manager::class)->withPivot('hired_on', 'fired_on')->withTimestamps();
    }

    /**
     * A wrestler can hold many titles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function titles()
    {
        return $this->hasMany(Champion::class);
    }

    /**
     * A wrestler can have many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function matches()
	{
		return $this->belongsToMany(Match::class);
	}

    /**
     * A wrestler can have many injuries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function injuries()
	{
		return $this->hasMany(Injury::class);
	}

    /**
     * A wrestler can have many suspensions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suspensions()
    {
        return $this->hasMany(Suspension::class);
    }

    /**
     * A wrestler can have many retirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function retirements()
	{
		return $this->hasMany(Retirement::class);
	}

    /**
     * Retrieves the status id attribute.
     *
     * @return mixed integer
     */
	public function status()
    {
        return $this->getAttribute('status_id');
    }

    /**
     * Retrieves date of the wrestler's first match.
     *
     * @return string
     */
    public function firstMatchDate()
    {
        return $this->matches->first()->date;
    }

    /**
     * Checks to see if wrestler is no longer retired, injured or suspended.
     *
     * @return void
     */
    //public function statusChanged()
    //{
    //    if ($this->status() == WrestlerStatus::RETIRED) {
    //        $this->unretire();
    //    } else if ($this->status() == WrestlerStatus::INJURED) {
    //        $this->heal();
    //    } else if ($this->status() == WrestlerStatus::SUSPENDED) {
    //        $this->rejoin();
    //    }
    //}

    public function hasMatches()
    {
        return $this->matches->isNotEmpty();
    }
}

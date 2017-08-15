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

class Wrestler extends Model
{
	use HasStatuses, HasManagers, HasTitles, HasRetirements, HasSuspensions, HasInjuries;

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
        return $this->hasMany(TitleHistory::class);
    }

    /**
     * A wrestler can have many wrestles.
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
		return $this->hasMany(WrestlerInjury::class);
	}

    /**
     * A wrestler can have many injuries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suspensions()
    {
        return $this->hasMany(WrestlerSuspension::class);
    }

    /**
     * A wrestler can have many retirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function retirements()
	{
		return $this->hasMany(WrestlerRetirement::class);
	}

    /**
     * A wrestler can have many wrestles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function status() {
        return $this->getAttribute('status_id');
    }

    /**
     * Get the wrestler's height in feet.
     *
     * @return integer
     */
    public function getHeightInFeetAttribute()
    {
        if ($this->height)
        {
            return floor($this->height/12);
        }

        return null;
    }

    public function getFormattedHeightAttribute()
    {
        $feet = floor($this->height / 12);
        $inches = ($this->height % 12);

        return $feet.'\''.$inches.'"';
    }

    /**
     * Get the wrestler's remaining height in inches.
     *
     * @return integer
     */
    public function getHeightInInchesAttribute()
    {
        if ($this->height)
        {
            return $this->height % 12;
        }

        return null;
    }

    /**
     * Set the height field for the wrestler.
     *
     * @param $value integer
     * @return integer
     */
    public function setHeightAttribute($value)
    {
        return $this->attributes['height'] = $value;
    }

    public function statusChanged()
    {
        if ($this->status() == WrestlerStatus::RETIRED) {
            $this->unretire();
        } else if ($this->status() == WrestlerStatus::INJURED) {
            $this->heal();
        } else if ($this->status() == WrestlerStatus::SUSPENDED) {
            $this->rejoin();
        }
    }


}

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
     * A wrestler can have one bio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bio()
    {
        return $this->hasOne(WrestlerBio::class);
    }

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
}

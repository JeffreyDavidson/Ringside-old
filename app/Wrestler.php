<?php

namespace App;

use Carbon\Carbon;
use App\Exceptions\WrestlerCanNotBeHealedException;
use Illuminate\Database\Eloquent\Model;

class Wrestler extends Model
{
    protected $guarded = [];

    public function bio()
    {
        return $this->hasOne(WrestlerBio::class);
    }

    public function managers()
    {
        return $this->belongsToMany(Manager::class)->withPivot('hired_on', 'fired_on')->withTimestamps();
    }

    public function previousManagers()
    {
        return $this->managers()->wherePivot('fired_on', '<', Carbon::now());
    }

    public function currentManagers()
    {
        return $this->managers()->wherePivot('fired_on', '=', null);
    }

    public function hireManager($manager)
    {
        return $this->managers()->attach($manager->id, ['hired_on' => Carbon::now()]);
    }

    public function fireManager($manager)
    {
        return $this->managers()->updateExistingPivot($manager->id, ['fired_on' => Carbon::now()]);
    }

    public function titles()
    {
        return $this->hasMany(TitleHistory::class)->with('title');
    }

    public function winTitle($title)
    {
        $this->titles()->create(['title_id' => $title->id, 'won_on' => Carbon::now()]);
    }

    public function loseTitle($title)
    {
        return $this->titles()->whereTitleId($title->id)->whereNull('lost_on')->update(['lost_on' => Carbon::now()]);
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }

    public function injuries()
    {
        return $this->hasMany(WrestlerInjury::class);
    }

    public function injure()
    {
        $this->update(['status_id' => 3]);

        $this->injuries()->create(['injured_at' => Carbon::now()]);
    }

    public function heal()
    {
        if ($this->status_id != 3)
        {
            throw new WrestlerCanNotBeHealedException;
        }

        $this->update(['status_id' => 1]);

        $this->injuries()->whereNull('healed_at')->first()->healed();
    }

    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status_id', 2);
    }

    public function scopeInjured($query)
    {
        return $query->where('status_id', 3);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status_id', 4);
    }

    public function scopeRetired($query)
    {
        return $query->where('status_id', 5);
    }
}

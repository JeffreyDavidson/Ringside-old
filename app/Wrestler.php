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
        return $this->belongsToMany(TitleHistory::class);
    }

    public function winTitle($title, $date = null)
    {
        if (! $date) {
            $date = Carbon::now();
        }

        $this->titles()->create(['title_id' => $title->id, 'won_on' => $date]);

        return $this;
    }

    public function loseTitle($title, $date = null)
    {
        if (! $date) {
            $date = Carbon::now();
        }

        $this->titles()->whereTitleId($title->id)->whereNull('lost_on')->first()->loseTitle($date);

        return $this;
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
        return $this->hasMany(WrestlerRetire::class);
    }

    public function injure($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        $this->update(['status_id' => 3]);

        $this->injuries()->create(['injured_at' => $date]);

        return $this;
    }

    public function heal($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if ($this->status_id != 3)
        {
            throw new WrestlerCanNotBeHealedException;
        }

        $this->update(['status_id' => 1]);

        $this->injuries()->whereNull('healed_at')->first()->healed($date);
    }

    public function retire($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        $this->update(['status_id' => 5]);

        $this->retirements()->create(['retired_at' => $date]);

        return $this;
    }

    public function unretire($date = null)
    {
        if(! $date) {
            $date = Carbon::now();
        }

        if ($this->status_id != 5)
        {
            throw new WrestlerCanNotRetireException;
        }

        $this->update(['status_id' => 1]);

        $this->retirements()->whereNull('ended_at')->first()->unretire($date);
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

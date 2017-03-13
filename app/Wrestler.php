<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Wrestler extends Model
{
    protected $guarded = [];

    public function getFormattedHeightAttribute()
    {
        $feet = floor($this->height / 12);
        $inches = ($this->height % 12);

        return $feet.'\''.$inches.'"';
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

    public function getTitlesAttribute()
    {
        return Title::all()->filter(function ($title) {
            return ($title->currentHolder)
                        ? $title->currentHolder->is($this)
                        : false;
        });
    }

    public function winTitle($title)
    {
        return $title->winTitle($this);
    }
}

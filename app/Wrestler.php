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

    public function titles()
    {
        return $this->belongsToMany(Title::class)->withPivot('won_on', 'lost_on')->withTimestamps();
    }

    public function groupedTitles()
    {
        return $this->titles()->groupBy('title_id')->get();
    }

    public function winTitle($title)
    {
        return $this->titles()->attach($title->id, ['won_on' => Carbon::now()]);
    }

    public function loseTitle($title)
    {
        return $this->titles()->wherePivot('lost_on', null)->updateExistingPivot($title->id, ['lost_on' => Carbon::now()]);
    }

}

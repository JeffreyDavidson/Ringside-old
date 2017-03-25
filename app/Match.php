<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function competitors()
    {
        return $this->belongsToMany(Wrestler::class);
    }

    public function addCompetitor($wrestler)
    {
        return $this->competitors()->attach($wrestler->id);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

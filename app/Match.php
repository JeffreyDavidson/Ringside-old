<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $guarded = [];

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

    public function type()
    {
        return $this->belongsTo(MatchType::class, 'match_type_id');
    }
}

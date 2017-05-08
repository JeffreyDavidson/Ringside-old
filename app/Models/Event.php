<?php

namespace App\Models;

use App\Exceptions\MatchesHaveSameMatchNumberAtEventException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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
    protected $dates = ['date'];

    public function getFormattedDateAttribute()
    {
        return $this->date->format('F jS, Y');
    }

    public function getFormattedFormDateAttribute()
    {
        return $this->date ? $this->date->format('m/d/Y') : null;
    }

    public function getTimeAttribute()
    {
        return $this->date ? $this->date->format('h:ia') : null;
    }

    public function matches()
    {
        return $this->hasMany(Match::class)
                ->with('type', 'referees', 'stipulations', 'wrestlers', 'titles');
    }

    public function arena()
    {
        return $this->belongsTo(Arena::class)->withTrashed();
    }

    public function addMatches($matches)
    {
        if($matches instanceof Match) {
            $matches = collect([$matches]);
        } else if(is_array($matches) && array_key_exists('match_number',$matches)) {
            $matches = collect([Match::create($matches)]);
        } else if(is_array($matches) && $matches[0] instanceof Match) {
            $matches = collect($matches);
        } else if(is_array($matches) && is_array($matches[0])) {
            $matches = collect($matches)->map(function($match) {
                return Match::create($match);
            });
        }

        try {
            $this->matches()->saveMany($matches->all());
        } catch (\PDOException $e) {
            throw new MatchesHaveSameMatchNumberAtEventException;
        }
    }

    /**
     * Set the date field for the title.
     *
     * @return date
     */
    public function setDateAttribute($date)
    {
        if($date instanceof Carbon) {
            return $this->attributes['date'] = $date;
        }

        return $this->attributes['date'] = Carbon::parse($date);
    }
}

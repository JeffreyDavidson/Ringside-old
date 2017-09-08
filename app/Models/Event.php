<?php

namespace App\Models;

use App\Exceptions\MatchesHaveSameMatchNumberAtEventException;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Presentable;

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\EventPresenter';

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

    public function matches()
    {
        return $this->hasMany(Match::class)->with('type', 'referees', 'stipulations', 'wrestlers', 'titles');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class)->withTrashed();
    }

    public function addMatches($matches)
    {
        if ($matches instanceof Match) {
            $matches = collect([$matches]);
        } else {
            if (is_array($matches) && array_key_exists('match_number', $matches)) {
                $matches = collect([Match::create($matches)]);
            } else {
                if (is_array($matches) && $matches[0] instanceof Match) {
                    $matches = collect($matches);
                } else {
                    if (is_array($matches) && is_array($matches[0])) {
                        $matches = collect($matches)->map(function ($match) {
                            return Match::create($match);
                        });
                    }
                }
            }
        }

        try {
            $this->matches()->saveMany($matches->all());
        } catch (\PDOException $e) {
            throw new MatchesHaveSameMatchNumberAtEventException;
        }
    }

    public function addMatch($match)
    {
        $this->matches()->save($match);
    }

    /**
     * Set the date field for the title.
     *
     * @return date
     */
    public function setDateAttribute($date)
    {
        return $this->attributes['date'] = $date;
    }

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    public function mainEvent()
    {
        return $this->matches->last();
    }
}

<?php

namespace App\Models;

use App\Exceptions\WrestlerNotQualifiedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracodes\Presenter\Traits\Presentable;

class Match extends Model
{
    use Presentable, SoftDeletes;

    protected $with = ['wrestlers', 'stipulations', 'referees', 'titles'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\MatchPresenter';

    /**
     * List of matches that need multiple referees.
     *
     * @var array
     */
    protected $matchTypesWithMultipleReferees = [
        'battleroyal',
        'royalrumble',
    ];

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A match can have many wrestlers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wrestlers()
    {
        return $this->belongsToMany(Wrestler::class)->withTimestamps();
    }

    /**
     * A match is assigned to an event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * A match has a type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(MatchType::class, 'match_type_id');
    }

    /**
     * A match can have many titles competed for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function titles()
    {
        return $this->belongsToMany(Title::class)->withTimestamps();
    }

    /**
     * A match can have many referees.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referees()
    {
        return $this->belongsToMany(Referee::class)->withTimestamps();
    }

    /**
     * A match can have many stipulations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stipulations()
    {
        return $this->belongsToMany(Stipulation::class)->withTimestamps();
    }

    /**
     * Add a wrestler to a match.
     *
     * @param Wrestler $wrestler
     *
     * @throws WrestlerNotQualifiedException
     */
    public function addWrestler(Wrestler $wrestler)
    {
        $this->wrestlers()->save($wrestler);
    }

    /**
     * Add a collection of wrestlers to a match.
     *
     * @param $wrestlers
     */
    public function addWrestlers($wrestlers)
    {
        $this->wrestlers()->saveMany($wrestlers);
    }

    /**
     * Add a title to a match.
     *
     * @param Title $title
     */
    public function addTitle(Title $title)
    {
        $this->titles()->save($title);
    }

    /**
     * Add a collection of titles to a match.
     *
     * @param $titles
     */
    public function addTitles($titles)
    {
        $this->titles()->saveMany($titles->all());
    }

    /**
     * Add a stipulation to a match.
     *
     * @param Stipulation $stipulation
     */
    public function addStipulation(Stipulation $stipulation)
    {
        $this->stipulations()->save($stipulation);
    }

    /**
     * Add a collection of stipulations to a match.
     *
     * @param $stipulations
     */
    public function addStipulations($stipulations)
    {
        $this->stipulations()->saveMany($stipulations);
    }

    /**
     * Add a referee to a match.
     *
     * @param Referee $referee
     */
    public function addReferee(Referee $referee)
    {
        $this->referees()->save($referee);
    }

    /**
     * Add a collection of referees to a match.
     *
     * @param $referees
     */
    public function addReferees($referees)
    {
        $this->referees()->saveMany($referees);
    }

    /**
     * Determines if the match has a title associated to it.
     *
     * @return bool
     */
    public function isTitleMatch()
    {
        return $this->titles->isNotEmpty();
    }

    /**
     * Sets the winner of the match.
     *
     * @param Wrestler $wrestler
     */
    public function setWinner(Wrestler $wrestler)
    {
        $this->update(['winner_id' => $wrestler->id, 'loser_id' => $this->wrestlers->except($wrestler->id)->first()->id]);
        if ($this->isTitleMatch()) {
            $this->titles->each(function ($title) use ($wrestler) {
                if (!$wrestler->hasTitle($title)) {
                    $title->setNewChampion($wrestler, $this->event->date);
                }
            });
        }
    }

    /**
     * Retrieves the date of the event for the match.
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return $this->event->date;
    }

    public function isPast()
    {
        return $this->date->isPast();
    }

    /**
     * Checks to if the match type needs multiple referees.
     *
     * @return bool
     */
    public function needsMoreThanOneReferee()
    {
        return in_array($this->type->slug, $this->matchTypesWithMultipleReferees);
    }
}

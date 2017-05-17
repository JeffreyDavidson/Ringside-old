<?php

namespace App\Models;

use App\Exceptions\WrestlerNotQualifiedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Match extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A match can have many wrestles.
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
     * A match can be competed for  many titles.
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
     * @throws WrestlerNotQualifiedException
     */
	public function addWrestler(Wrestler $wrestler)
    {
        if ($wrestler->hired_at > $this->event->date)
        {
            throw new WrestlerNotQualifiedException;
        }

        $this->wrestlers()->save($wrestler);
	}

    /**
     * Add titles to a match.
     *
     * @param $titles
     */
    public function addTitles($titles)
    {
        if($titles instanceof Title) {
            $titles = collect([$titles]);
        } else if(is_array($titles) && $titles[0] instanceof Title) {
            $titles = collect($titles);
        }

        $this->titles()->saveMany($titles->all());
    }

    /**
     * Add stipulations to a match.
     *
     * @param $stipulations
     */
    public function addStipulations($stipulations)
    {
        if($stipulations instanceof Stipulation) {
            $stipulations = collect([$stipulations]);
        } else if(is_array($stipulations) && $stipulations[0] instanceof Stipulation) {
            $stipulations = collect($stipulations);
        }

        $this->stipulations()->saveMany($stipulations->all());
    }

    /**
     * Add referees to a match.
     *
     * @param $referees
     */
    public function addReferees($referees)
    {
        if($referees instanceof Referee) {
            $referees = collect([$referees]);
        } else if(is_array($referees) && $referees[0] instanceof Referee) {
            $referees = collect($referees);
        }

        $this->referees()->saveMany($referees->all());
    }

    /**
     * Determine if the current match is a title match.
     *
     * @return boolean
     */
	public function isTitleMatch()
    {
        return $this->titles()->count() > 0;
    }

    /**
     * Set the winner of the match.
     *
     * @param $winner
     */
    public function setWinner($winner)
    {
        $this->update(['winner_id' => $winner->id, 'loser_id' => $this->wrestlers->except($winner->id)->first()->id]);

        if ($this->isTitleMatch())
        {
            $this->titles->each(function ($title) use ($winner) {
                if (!$winner->hasTitle($title)) {
                    $title->setNewChampion($winner, $this->event->date);
                }
            });
        }
    }

	public function getWinner() {
		return Wrestler::find($this->winner_id);
    }
}

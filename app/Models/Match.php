<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Match extends Model
{
    use Presentable, SoftDeletes;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['wrestlers', 'stipulation', 'referees', 'titles'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\MatchPresenter';

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
        return $this->belongsToMany(Wrestler::class)->withPivot('side_number');
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
     * A match can have many titles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function titles()
    {
        return $this->belongsToMany(Title::class);
    }

    /**
     * A match can have many referees.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referees()
    {
        return $this->belongsToMany(Referee::class);
    }

    /**
     * A match can have a stipulation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stipulation()
    {
        return $this->belongsTo(Stipulation::class);
    }

    /**
     * A match has a type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function decision()
    {
        return $this->belongsTo(MatchDecision::class, 'match_decision_id');
    }

    /**
     * A match can have many losers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function winner()
    {
        return $this->belongsTo(Wrestler::class);
    }

    /**
     * A match can have many losers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function losers()
    {
        return $this->belongsToMany(Wrestler::class, 'match_loser');
    }

    /**
     * Add a wrestler to a match.
     *
     * @param \App\Models\Wrestler $wrestler
     */
    public function addWrestler(Wrestler $wrestler, $sideNumber)
    {
        $this->wrestlers()->attach($wrestler->id, ['side_number' => $sideNumber]);
    }

    /**
     * Add a array of wrestlers to a match.
     *
     * @param array $wrestlers
     */
    public function addWrestlers($wrestlers)
    {
        foreach ($wrestlers as $sideNumber => $wrestlersGroup) {
            foreach ($wrestlersGroup as $wrestler) {
                $this->addWrestler($wrestler, $sideNumber);
            }
        }
    }

    /**
     * Add a title to a match.
     *
     * @param \App\Models\Title $title
     */
    public function addTitle(Title $title)
    {
        $this->titles()->save($title);
    }

    /**
     * Add a array of titles to a match.
     *
     * @param array $titles
     */
    public function addTitles($titles)
    {
        $this->titles()->saveMany($titles);
    }

    /**
     * Add a stipulation to a match.
     *
     * @param \App\Models\Stipulation $stipulation
     */
    public function addStipulation(Stipulation $stipulation)
    {
        $this->update(['stipulation_id' => $stipulation->id]);
    }

    /**
     * Add a referee to a match.
     *
     * @param \App\Models\Referee $referee
     */
    public function addReferee(Referee $referee)
    {
        $this->referees()->save($referee);
    }

    /**
     * Add an array of referees to a match.
     *
     * @param array $referees
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
     * @param \App\Models\Wrestler $wrestler
     * @return
     */
    public function setWinner(Wrestler $wrestler, $matchDecisionSlug)
    {
        $matchDecision = MatchDecision::where('slug', $matchDecisionSlug)->first();

        $this->update([
            'match_decision_id' => $matchDecision->id,
            'winner_id' => $wrestler->id,
            'loser_id' => $this->wrestlers->except($wrestler->id)->first()->id,
        ]);

        if ($this->isTitleMatch()) {
            $this->titles->each(function ($title) use ($wrestler) {
                if (! $wrestler->hasTitle($title) && $this->decision->titleCanChangeHands()) {
                    $title->setChampion($wrestler, $this->date);
                } else {
                    $title->currentChampion->increment('successful_defenses');
                }
            });
        }

        return $this;
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

    /**
     * Add a match to an event.
     *
     * @param  \App\Models\Event $event
     * @return bool
     */
    public function addToEvent(Event $event)
    {
        $nextMatchNumber = $event->matches->max('match_number');

        return $this->update(['event_id' => $event->id, 'match_number' => $nextMatchNumber + 1]);
    }

    public function scopeForEvent(Builder $query, Event $event)
    {
        return $query->where('event_id', $event->id);
    }

    public function scopeWithMatchNumber(Builder $query, $matchNumber)
    {
        return $query->where('match_number', $matchNumber);
    }
}

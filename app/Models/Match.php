<?php

namespace App\Models;

use App\Interfaces\Competitor;
use App\Models\Roster\Referee;
use App\Models\Roster\TagTeam;
use App\Models\Roster\Wrestler;
use App\Presenters\MatchPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Match extends Model
{
    use Presentable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['match_number', 'match_type_id', 'stipulation_id', 'match_decision_id', 'preview', 'result'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = MatchPresenter::class;

    /**
     * A match has a decision.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function decision()
    {
        return $this->belongsTo(MatchDecision::class, 'match_decision_id');
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
     * A match can have many losers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function losers()
    {
        return $this->belongsToMany(Wrestler::class, 'match_loser');
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
     * A match can have many titles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function titles()
    {
        return $this->belongsToMany(Title::class);
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
     * A match can have many winners.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function winners()
    {
        return $this->belongsToMany(Wrestler::class, 'match_winner');
    }

    /**
     * A match can have many wrestlers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphedByMany
     */
    public function wrestlers()
    {
        return $this->morphedByMany(Wrestler::class, 'competitor');
    }

    /**
     * A match can have many tag teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphedByMany
     */
    public function tagteams()
    {
        return $this->morphedByMany(TagTeam::class, 'competitor');
    }

    public function getCompetitorsAttribute()
    {
        return $this->wrestlers->merge($this->tagteams);
    }

    /**
     * Add a referee to a match.
     *
     * @param  \App\Models\Roster\Referee  $referee
     * @return void
     */
    public function addReferee(Referee $referee)
    {
        $this->referees()->save($referee);
    }

    /**
     * Add referees to a match.
     *
     * @param  array  $referees
     * @return void
     */
    public function addReferees($referees)
    {
        foreach ($referees as $refereeId) {
            $this->referees()->attach($refereeId);
        }
    }

    /**
     * Add a stipulation to a match.
     *
     * @param  \App\Models\Stipulation  $stipulation
     * @return void
     */
    public function addStipulation(Stipulation $stipulation)
    {
        $this->stipulation()->associate($stipulation);
        $this->save();
    }

    /**
     * Add a title to a match.
     *
     * @param  \App\Models\Title  $title
     * @return void
     */
    public function addTitle(Title $title)
    {
        $this->titles()->save($title);
    }

    /**
     * Add titles to a match.
     *
     * @param  array  $titles
     * @return void
     */
    public function addTitles($titles)
    {
        $this->titles()->attach($titles);
    }

    /**
     * Add a match to an event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function addToEvent(Event $event)
    {
        $this->event()->associate($event);
        $this->save();
    }

    public function addCompetitor(Competitor $competitor, int $sideNumber)
    {
        if ($competitor instanceof Wrestler) {
            $this->wrestlers()->attach($competitor, ['side_number' => $sideNumber]);
        } elseif ($competitor instanceof TagTeam) {
            $this->tagteams()->attach($competitor, ['side_number' => $sideNumber]);
        }
    }

    public function addCompetitors(array $allCompetitors)
    {
        // dd($competitors);
        foreach($allCompetitors as $sideNumber => $competitors) {
            foreach ($competitors as $competitor) {
                if ($competitor instanceof Wrestler) {
                    $this->wrestlers()->attach($competitor, ['side_number' => $sideNumber]);
                } elseif ($competitor instanceof TagTeam) {
                    $this->tagteams()->attach($competitor, ['side_number' => $sideNumber]);
                }
            }
        }
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
     * Adds the losers of the match.
     *
     * @return $this
     */
    public function setLosers($losers)
    {
        $this->losers()->sync($losers);

        return $this;
    }

    /**
     * Adds the winners of a match.
     *
     * @return $this
     */
    public function setWinners($winners)
    {
        $this->winners()->sync($winners);

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
     * Scope a query to only include matches for a specific event.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForEvent(Builder $query, Event $event)
    {
        return $query->where('event_id', $event->id);
    }

    /**
     * Scope a query to only include matches with a specific match number.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $matchNumber
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMatchNumber(Builder $query, $matchNumber)
    {
        return $query->where('match_number', $matchNumber);
    }

    /**
     * Scope a query to only include matches with a specific wrestler.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCompetitor(Builder $query, $id)
    {
        return $query->whereHas('competitors', function ($query) use ($id) {
            $query->where('wrestlers.id', $id);
        });
    }
}

<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Title;
use App\Models\Championship;
use App\Interfaces\Competitor;
use Facades\ChampionshipFactory;
use App\Exceptions\ModelIsTitleChampionException;
use App\Exceptions\ModelNotTitleChampionException;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait CompetitorTrait
{
    /**
     * The titles that a model has held in the past.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getPastTitlesHeldAttribute(): BelongsToMany
    {
        return $this->championships()->wherePivot('lost_on', '!=', null)->get();
    }

    /**
     * Checks to see if a model used to hold any titles.
     *
     * @return boolean
     */
    public function hasPastTitlesHeld(): boolean
    {
        return $this->pastTitlesHeld->exists();
    }

    /**
     * The titles currently held by a model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function getCurrentTitlesHeldAttribute()
    {
        return $this->championships()->wherePivot('lost_on', null)->get();
    }

    /**
     * Determines if a model is currently a champion.
     *
     * @return boolean
     */
    public function isCurrentlyAChampion(): boolean
    {
        return $this->currentTitlesHeld->isNotEmpty();
    }

    /**
     * Checks to see if the wrestler is the holder of a specific title.
     *
     * @param  \App\Models\Title  $title
     * @return boolean
     */
    public function hasTitle(Title $title): boolean
    {
        return $this->currentTitlesHeld->where('title_id', $title->id)->exists();
    }

    /**
     * A model can win a title.
     *
     * @param  \App\Models\Title  $title
     * @param  string  $date
     * @return $this
     *
     * @throws \App\Exceptions\ModelIsTitleChampionException
     */
    public function winTitle(Title $title, $date): Competitor
    {
        if (!empty($title->currentChampion) && $title->currentChampion->is($this)) {
            throw new ModelIsTitleChampionException;
        }

        $this->championships()->attach($title->id, ['won_on' => $date]);

        return $this;
    }

    /**
     * A model can lose a title.
     *
     * @param  \App\Models\Title  $title
     * @param  string  $date
     * @return $this
     *
     * @throws \App\Exceptions\ModelNotTitleChampionException
     */
    public function loseTitle(Title $title, $date): Competitor
    {
        if (empty($title->currentChampion) || !$title->currentChampion->is($this)) {
            throw new ModelNotTitleChampionException;
        }

        $this->championships()->updateExistingPivot($title->id, ['lost_on' => $date]);

        return $this;
    }

    /**
     * A wrestler can have many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }

    /**
     * Retrieves the date of the model's first match.
     *
     * @return string|null
     */
    public function getFirstMatchDateAttribute()
    {
        return $this->matches()
            ->select('events.date as first_date')
            ->join('events', 'matches.event_id', '=', 'events.id')
            ->orderBy('events.date')
            ->value('first_date');
    }

    /**
     * Returns the model's past matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getPastMatchesAttribute(): HasMany
    {
        return $this->matches()->whereHas('event', function ($query) {
            $query->where('date', '<', Carbon::today());
        })->get();
    }

    /**
     * Checks to see if the model has past matches.
     *
     * @return boolean
     */
    public function hasPastMatches(): boolean
    {
        return $this->pastMatches->isNotEmpty();
    }

    /**
     * Returns the model's past matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getScheduledMatchesAttribute(): HasMany
    {
        return $this->matches()->whereHas('event', function ($query) {
            $query->where('date', '>=', Carbon::today());
        })->get();
    }
}
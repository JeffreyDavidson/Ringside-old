<?php

namespace App\Traits;

use App\Models\Title;
use App\Exceptions\WrestlerAlreadyHasTitleException;
use App\Exceptions\WrestlerNotTitleChampionException;

trait HasTitles
{
    abstract public function championships();

    /**
     * Checks to see if the wrestler has held any previous titles.
     *
     * @return bool
     */
    public function hasPastTitlesHeld()
    {
        return $this->pastTitlesHeld()->isNotEmpty();
    }

    /**
     * Returns the wrestler's past titles held.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastTitlesHeld()
    {
        return $this->championships()->whereNotNull('lost_on')->with('title')->get()->pluck('title');
    }

    /**
     * Checks to see if the wrestler is the champion of a specific title.
     *
     * @param \App\Models\Title $title
     * @return Boolean
     */
    public function hasTitle(Title $title)
    {
        return $this->currentTitlesHeld()->contains(function ($currentTitle) use ($title) {
            return $currentTitle->is($title);
        });
    }

    /**
     * Retrieves a collection of titles currently held by wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function currentTitlesHeld()
    {
        return $this->championships()->whereNull('lost_on')->with('title')->get()->pluck('title');
    }

    /**
     * A wrestler can win a title.
     *
     * @param \App\Models\Title $title
     * @param datetime $date
     * @return bool
     */
    public function winTitle(Title $title, $date)
    {
        if ($this->hasTitle($title)) {
            throw new WrestlerAlreadyHasTitleException;
        }

        $this->championships()->create(['title_id' => $title->id, 'won_on' => $date]);
    }

    /**
     * A wrestler can lose a title.
     *
     * @param \App\Models\Title $title
     * @param datetime $date
     * @return bool
     */
    public function loseTitle(Title $title, $date)
    {
        if (! $this->hasTitle($title)) {
            throw new WrestlerNotTitleChampionException;
        }

        $titleHeld = $this->currentTitlesHeld()->firstWhere('id', $title->id);

        return $titleHeld->currentChampion->update(['lost_on' => $date ?: $this->freshTimestamp()]);
    }

    /**
     * Determines if a wrestler is a champion.
     *
     * @return bool
     */
    public function isCurrentlyAChampion()
    {
        return $this->currentTitlesHeld()->count() > 0;
    }
}

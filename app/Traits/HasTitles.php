<?php

namespace App\Traits;

use App\Models\Title;
use App\Exceptions\WrestlerAlreadyHasTitleException;
use App\Exceptions\WrestlerNotTitleChampionException;

trait HasTitles
{
    abstract public function titles();

    /**
     * Checks to see if the wrestler has held any previous titles.
     *
     * @return bool
     */
    public function hasPastTitlesHeld()
    {
        return $this->pastTitlesHeld->isNotEmpty();
    }

    /**
     * Returns the wrestler's past titles held.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastTitlesHeld()
    {
        return $this->titles()->whereNotNull('lost_on');
    }

    public function hasTitle($title)
    {
        $this->load('currentTitlesHeld.title');

        return $this->currentTitlesHeld->contains(function ($champion) use ($title) {
            return $champion->title->is($title);
        });
    }

    public function currentTitlesHeld()
    {
        return $this->titles()->whereNull('lost_on');
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

        $this->titles()->create(['title_id' => $title->id, 'won_on' => $date]);
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

        $titleHeld = $this->currentTitlesHeld()->where('title_id', $title->id)->first();

        return $titleHeld->update(['lost_on' => $date ?: $this->freshTimestamp()]);
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

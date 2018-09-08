<?php

namespace App\Traits;

use App\Models\Title;
use App\Models\Championship;
use App\Exceptions\WrestlerAlreadyHasTitleException;
use App\Exceptions\WrestlerNotTitleChampionException;

trait HasTitles
{
    /**
     * All of the titles this wrestler has held or currently holds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function titles()
    {
        return $this->belongsToMany(Title::class, 'championships')->using(Championship::class);
    }

    /**
     * The titles that this wrestler used to hold.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pastTitlesHeld()
    {
        return $this->titles()->wherePivot('lost_on', '!=', null);
    }

    /**
     * Checks to see if the wrestler used to hold any titles.
     *
     * @return bool
     */
    public function hasPastTitlesHeld()
    {
        return $this->pastTitlesHeld()->exists();
    }

    /**
     * The titles currently held by the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentTitlesHeld()
    {
        return $this->titles()->wherePivot('lost_on', null);
    }

    /**
     * Determines if a wrestler is currently a champion.
     *
     * @return bool
     */
    public function isCurrentlyAChampion()
    {
        return $this->currentTitlesHeld()->exists();
    }

    /**
     * Checks to see if the wrestler is the holder of a specific title.
     *
     * @param  \App\Models\Title  $title
     * @return bool
     */
    public function hasTitle(Title $title)
    {
        return $this->currentTitlesHeld()->where('title_id', $title->id)->exists();
    }

    /**
     * A wrestler can win a title.
     *
     * @param  \App\Models\Title  $title
     * @param  string  $date
     * @return $this
     *
     * @throws App\Exceptions\WrestlerAlreadyHasTitleException
     */
    public function winTitle(Title $title, $date)
    {
        if ($this->hasTitle($title)) {
            throw new WrestlerAlreadyHasTitleException;
        }

        $this->titles()->attach([
            $title->id => ['won_on' => $date],
        ]);

        return $this;
    }

    /**
     * A wrestler can lose a title.
     *
     * @param  \App\Models\Title  $title
     * @param  string  $date
     * @return $this
     *
     * @throws App\Exceptions\WrestlerNotTitleChampionException
     */
    public function loseTitle(Title $title, $date)
    {
        if (!$this->hasTitle($title)) {
            throw new WrestlerNotTitleChampionException;
        }

        $this->titles()->updateExistingPivot($title->id, [
            'lost_on' => $date,
        ]);

        return $this;
    }
}

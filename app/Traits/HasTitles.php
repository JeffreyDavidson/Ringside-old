<?php

namespace App\Traits;

use App\Models\Title;
use App\Models\Championship;
use App\Exceptions\ModelIsTitleChampionException;
use App\Exceptions\ModelNotTitleChampionException;

trait HasTitles
{
    /**
     * All of the titles this wrestler has held or currently holds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function titles()
    {
        return $this->belongsToMany(Title::class, 'championships')->using(Championship::class)->withPivot('id', 'won_on', 'lost_on', 'successful_defenses');
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
     * @throws App\Exceptions\ModelIsTitleChampionException
     */
    public function winTitle(Title $title, $date)
    {
        if (! empty($title->currentChampion)) {
            if ($title->currentChampion->is($this)) {
                throw new ModelIsTitleChampionException;
            }
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
     * @throws App\Exceptions\ModelNotTitleChampionException
     */
    public function loseTitle(Title $title, $date)
    {
        if (empty($title->currentChampion) || ! $title->currentChampion->is($this)) {
            throw new ModelNotTitleChampionException;
        }

        $this->titles()->updateExistingPivot($title->id, [
            'lost_on' => $date,
        ]);

        return $this;
    }
}

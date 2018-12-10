<?php

namespace App\Traits;

use App\Models\Title;
use App\Models\Championship;
use App\Exceptions\ModelIsTitleChampionException;
use App\Exceptions\ModelNotTitleChampionException;

/**
 * @mixin \Eloquent
 */
trait HasTitles
{
    /**
     * The titles that a model has held in the past.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pastTitlesHeld()
    {
        return $this->championships()->wherePivot('lost_on', '!=', null);
    }

    /**
     * Checks to see if a model used to hold any titles.
     *
     * @return bool
     */
    public function hasPastTitlesHeld()
    {
        return $this->pastTitlesHeld()->exists();
    }

    /**
     * The titles currently held by a model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentTitlesHeld()
    {
        return $this->championships()->wherePivot('lost_on', null);
    }

    /**
     * Determines if a model is currently a champion.
     *
     * @return bool
     */
    public function isCurrentlyAChampion()
    {
        return $this->currentTitlesHeld->isNotEmpty();
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
     * A model can win a title.
     *
     * @param  \App\Models\Title  $title
     * @param  string  $date
     * @return $this
     *
     * @throws \App\Exceptions\ModelIsTitleChampionException
     */
    public function winTitle(Title $title, $date)
    {
        if (! empty($title->currentChampion) && $title->currentChampion->is($this)) {
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
    public function loseTitle(Title $title, $date)
    {
        if (empty($title->currentChampion) || ! $title->currentChampion->is($this)) {
            throw new ModelNotTitleChampionException;
        }

        $this->championships()->updateExistingPivot($title->id, ['lost_on' => $date]);

        return $this;
    }
}

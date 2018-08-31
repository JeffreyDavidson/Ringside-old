<?php

namespace App\Traits;

use App\Models\Title;
use App\Models\Championship;
use App\Exceptions\WrestlerAlreadyHasTitleException;

trait HasTitles
{
    /**
     * A wrestler can hold many championships.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function championships()
    {
        return $this->hasMany(Championship::class);
    }

    /**
     * Checks to see if the wrestler has held any past titles.
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
     * @param  \App\Models\Title  $title
     * @return bool
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
     * @param  \App\Models\Title  $title
     * @param  string  $date
     * @return $this;
     */
    public function winTitle(Title $title, $date)
    {
        if ($this->hasTitle($title)) {
            throw new WrestlerAlreadyHasTitleException;
        }

        $this->championships()->create(['title_id' => $title->id, 'won_on' => $date]);

        return $this;
    }

    /**
     * Determines if a wrestler is currently a champion.
     *
     * @return bool
     */
    public function isCurrentlyAChampion()
    {
        return $this->currentTitlesHeld()->isNotEmpty();
    }
}

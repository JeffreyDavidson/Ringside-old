<?php

namespace App\Traits;

use App\Exceptions\WrestlerAlreadyHasTitleException;
use App\Exceptions\WrestlerNotTitleChampionException;

use Carbon\Carbon;

trait HasTitles
{
    abstract public function titles();

    public function hasPreviousTitlesHeld()
    {
        return $this->previousTitlesHeld->isNotEmpty();
    }

    public function previousTitlesHeld()
    {
        return $this->titles()->whereNotNull('lost_on');
    }

    public function isCurrentlyAChampion()
    {
        return $this->currentTitlesHeld()->count() > 0;
    }

    public function currentTitlesHeld()
    {
        return $this->titles()->whereNull('lost_on');
    }

    public function hasTitle($title)
    {
        $this->load('currentTitlesHeld.title');

        return $this->currentTitlesHeld->contains(function ($champion) use ($title) {
            return $champion->title->is($title);
        });
    }

    public function winTitle($title)
    {
        if ($this->hasTitle($title)) {
            throw new WrestlerAlreadyHasTitleException;
        }

        $this->titles()->create(['title_id' => $title->id, 'won_on' => Carbon::now()]);
    }

    public function loseTitle($title)
    {
        if (! $this->hasTitle($title)) {
            throw new WrestlerNotTitleChampionException;
        }

        $this->currentTitlesHeld()->where('title_id', $title->id)->first()->loseTitle();
    }
}
<?php

use Carbon\Carbon;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Championship;

class ChampionshipFactory
{
    private $wrestler;
    private $title = null;
    private $wonOn = null;
    private $lostOn = null;
    private $titleDefenses = 0;
    private $states = [];

    public function __construct()
    {
        $this->resetProperties();
    }

    public function states($states)
    {
        $this->states = $states;

        return $this;
    }

    public function create()
    {
        if (is_null($this->title)) {
            $this->title = factory(Title::class)->create();
            $this->wonOn = $this->title->introduced_at->copy()->addMonth();
        } elseif (!is_null($this->title) && is_null($this->wonOn)) {
            if ($this->title->champions()->exists()) {
                $dateLastChampionWon = $this->title->fresh()->currentChampion->pivot->won_on;
                $dateOfTitleChange = $dateLastChampionWon->copy()->addMonth();
                $this->title->fresh()->currentChampion->loseTitle($this->title, $dateOfTitleChange);
                $this->wonOn = $dateOfTitleChange;
            } else {
                $this->wonOn = $this->title->introduced_at->copy()->addMonth();
            }
        }

        $champion = factory(Championship::class)->states($this->states)->create([
            'wrestler_id' => $this->wrestler->id ?? factory(Wrestler::class)->create()->id,
            'title_id' => $this->title->id,
            'won_on' => $this->wonOn,
            'lost_on' => $this->lostOn,
            'successful_defenses' => $this->titleDefenses,
        ]);

        $this->resetProperties();

        return $champion;
    }

    public function forWrestler(Wrestler $wrestler)
    {
        $this->wrestler = $wrestler;

        return $this;
    }

    public function forTitle(Title $title)
    {
        $this->title = $title;

        return $this;
    }

    public function wonOn(Carbon $start)
    {
        $this->wonOn = $start;

        return $this;
    }

    public function lostOn(Carbon $end)
    {
        $this->lostOn = $end;

        return $this;
    }

    public function withSuccessfulTitleDefenses($count)
    {
        $this->titleDefenses = $count;

        return $this;
    }

    public function current()
    {
        $this->wonOn = Carbon::today()->subWeeks(2);
        $this->lostOn = null;

        return $this;
    }

    public function past()
    {
        $this->wonOn = Carbon::today()->subWeeks(2);
        $this->lostOn = Carbon::yesterday();

        return $this;
    }

    public function resetProperties()
    {
        if (!is_null($this->wrestler)) {
            $this->wrestler = null;
        }

        if (!is_null($this->title)) {
            $this->title = null;
        }

        if ($this->titleDefenses != 0) {
            $this->titleDefenses = 0;
        }

        if (! is_null($this->wonOn)) {
            $this->wonOn = null;
        }

        if (! is_null($this->lostOn)) {
            $this->lostOn = null;
        }
    }
}

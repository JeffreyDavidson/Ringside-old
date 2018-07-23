<?php

use App\Models\Championship;
use App\Models\Wrestler;
use App\Models\Title;
use Carbon\Carbon;

class ChampionshipFactory
{
    private $wrestler;
    private $title = null;
    private $wonOn = null;
    private $lostOn = null;
    private $titleDefenses = 0;

    public function __construct()
    {
        $this->resetProperties();
    }

    public function create()
    {
        if (is_null($this->title)) {
            $this->title = factory(Title::class)->create();
            $this->wonOn = $this->title->introduced_at->copy()->addMonth();
        } elseif (!is_null($this->title) && is_null($this->wonOn)) {
            if ($this->title->champions()->exists()) {
                $dateLastChampionWon = $this->title->fresh()->currentChampion->won_on;
                $dateOfTitleChange = $dateLastChampionWon->copy()->addMonth();
                $this->title->currentChampion->loseTitle($dateOfTitleChange);
                $this->wonOn = $dateOfTitleChange;
            } else {
                $this->wonOn = $this->title->introduced_at->copy()->addMonth();
            }
        }

        $champion = factory(Championship::class)->create([
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

    public function resetProperties()
    {
        if (! is_null($this->wrestler)) {
            $this->wrestler = null;
        }

        if (! is_null($this->title)) {
            $this->title = null;
        }

        if (! is_null($this->wonOn)) {
            $this->wonOn = null;
        }

        if (! is_null($this->lostOn)) {
            $this->lostOn = null;
        }

        if ($this->titleDefenses != 0) {
            $this->titleDefenses = 0;
        }
    }
}

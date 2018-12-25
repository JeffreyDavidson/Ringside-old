<?php

use Carbon\Carbon;
use App\Models\Manager;
use App\Models\TagTeam;
use App\Models\Wrestler;

class CompetitorHiringManagerFactory
{
    public $competitor = null;
    public $manager = null;
    public $hiredOn = null;
    public $firedOn = null;

    public function __construct()
    {
        $this->resetProperties();
    }

    public function create()
    {
        if (! is_null($this->manager)) {
            $this->manager = factory(Manager::class)->create([
                'hired_at' => $this->hiredOn->subWeeks(2),
            ]);
        }

        $this->wrestler->hireManager($this->manager, $this->hiredOn);

        if (! is_null($this->firedOn)) {
            $this->wrestler->fireManager($manager, $this->firedOn);
        }

        $this->resetProperties();

        return $manager;
    }

    public function withCompetitor(Competitor $competitor)
    {
        $this->competitor = $competitor;

        return $this;
    }

    public function hiringManager(Manager $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    public function hiredOn(Carbon $date)
    {
        $this->hiredOn = $date;

        return $this;
    }

    public function resetProperties()
    {
        if (! is_null($this->competitor)) {
            $this->competitor = null;
        }

        if (!is_null($this->manager)) {
            $this->manager = null;
        }

        if (! is_null($this->hiredOn)) {
            $this->hiredOn = null;
        }

        if (! is_null($this->firedOn)) {
            $this->firedOn = null;
        }
    }
}

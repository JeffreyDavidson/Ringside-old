<?php

use Carbon\Carbon;
use App\Models\Manager;
use App\Models\Wrestler;

class ManagerFactory
{
    public $wrestler = null;
    public $hiredOn = null;
    public $firedOn = null;
    public $states = null;

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
        $manager = factory(Manager::class)->create([
            'hired_at' => $this->hiredOn->subWeeks(2),
        ]);

        $this->wrestler->hireManager($manager, $this->hiredOn);

        if (!is_null($this->firedOn)) {
            $this->wrestler->fireManager($manager, $this->firedOn);
        }

        $this->resetProperties();

        return $manager;
    }

    public function forWrestler(Wrestler $wrestler)
    {
        $this->wrestler = $wrestler;

        return $this;
    }

    public function hiredOn(Carbon $date)
    {
        $this->hiredOn = $date;

        return $this;
    }

    public function firedOn(Carbon $date)
    {
        $this->firedOn = $date;

        return $this;
    }

    public function current()
    {
        $this->hiredOn = Carbon::today()->subWeeks(2);
        $this->firedOn = null;

        return $this;
    }

    public function past()
    {
        $this->hiredOn = Carbon::today()->subWeeks(2);
        $this->firedOn = Carbon::yesterday();

        return $this;
    }

    public function resetProperties()
    {
        if (!is_null($this->wrestler)) {
            $this->wrestler = null;
        }

        if (!is_null($this->hiredOn)) {
            $this->hiredOn = null;
        }

        if (!is_null($this->firedOn)) {
            $this->firedOn = null;
        }
    }
}

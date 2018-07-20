<?php

use App\Models\Manager;
use App\Models\Wrestler;
use Carbon\Carbon;

class ManagerFactory
{
    public $wrestler = null;
    public $hiredOn = null;
    public $firedOn = null;

    public function __construct()
    {
        $this->resetProperties();
    }

    public function create()
    {
        $manager = factory(Manager::class)->create([
            'hired_at' => $this->hiredOn->subWeeks(2)
        ]);

        $this->wrestler->hireManager($manager, $this->hiredOn);

        if (! is_null($this->firedOn)) {
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

    public function resetProperties()
    {
        if (! is_null($this->wrestler)) {
            $this->wrestler = null;
        }

        if (! is_null($this->hiredOn)) {
            $this->hiredOn = null;
        }

        if (! is_null($this->firedOn)) {
            $this->firedOn = null;
        }
    }
}

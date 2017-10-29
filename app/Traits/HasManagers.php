<?php

namespace App\Traits;

use App\Exceptions\WrestlerAlreadyHasManagerException;
use App\Exceptions\WrestlerNotHaveHiredManagerException;

trait HasManagers
{
    abstract public function managers();

    public function hasPreviousManagers()
    {
        return $this->previousManagers->isNotEmpty();
    }

    public function previousManagers()
    {
        return $this->managers()->whereNotNull('fired_on')->withPivot('fired_on');
    }

    public function hasCurrentManagers()
    {
        return $this->currentManagers->isNotEmpty();
    }

    public function hasManager($manager)
    {
        $this->load('currentManagers');

        return $this->currentManagers->contains($manager);
    }

    public function currentManagers()
    {
        return $this->managers()->wherePivot('fired_on', '=', null);
    }

    public function hireManager($manager)
    {
        if ($this->hasManager($manager)) {
            throw new WrestlerAlreadyHasManagerException;
        }

        return $this->managers()->attach($manager->id, ['hired_on' => $this->freshTimestamp()]);
    }

    public function fireManager($manager)
    {
        if (! $this->hasManager($manager)) {
            throw new WrestlerNotHaveHiredManagerException;
        }

        return $this->managers()->updateExistingPivot($manager->id, ['fired_on' => $this->freshTimestamp()]);
    }
}
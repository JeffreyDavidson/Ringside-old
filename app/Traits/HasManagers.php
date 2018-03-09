<?php

namespace App\Traits;

use App\Exceptions\WrestlerAlreadyHasManagerException;
use App\Exceptions\WrestlerNotHaveHiredManagerException;

trait HasManagers
{
    abstract public function managers();

    /**
     * Checks to see if the wrestler has past managers.
     *
     * @return bool
     */
    public function hasPastManagers()
    {
        return $this->pastManagers->isNotEmpty();
    }

    /**
     * Returns all the past managers for a wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastManagers()
    {
        return $this->managers()->whereNotNull('fired_on')->withPivot('fired_on');
    }

    /**
     * Checks to see if the wrestler has any current managers.
     *
     * @return bool
     */
    public function hasCurrentManagers()
    {
        return $this->currentManagers->isNotEmpty();
    }

    /**
     * Checks to see if the wrestler has a specific manager.
     *
     * @return bool
     */
    public function hasManager($manager)
    {
        $this->load('currentManagers');

        return $this->currentManagers->contains($manager);
    }

    /**
     * Returns the wrestler's current managers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function currentManagers()
    {
        return $this->managers()->wherePivot('fired_on', '=', null);
    }

    /**
     * A wrestler hires a given manager.
     *
     * @param \App\Models\Manager $manager
     * @return bool
     */
    public function hireManager($manager)
    {
        if ($this->hasManager($manager)) {
            throw new WrestlerAlreadyHasManagerException;
        }

        return $this->managers()->attach($manager->id, ['hired_on' => $this->freshTimestamp()]);
    }

    /**
     * A wrestler fires a given manager.
     *
     * @param \App\Models\Manager $manager
     * @return bool
     */
    public function fireManager($manager)
    {
        if (! $this->hasManager($manager)) {
            throw new WrestlerNotHaveHiredManagerException;
        }

        return $this->managers()->updateExistingPivot($manager->id, ['fired_on' => $this->freshTimestamp()]);
    }
}

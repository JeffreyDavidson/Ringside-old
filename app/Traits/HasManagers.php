<?php

namespace App\Traits;

use App\Models\Manager;
use App\Exceptions\ManagerNotHiredException;
use App\Exceptions\ModelHasManagerException;
use App\Exceptions\ModelIsInactiveException;

trait HasManagers
{
    /**
     * A wrestler can have many managers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(Manager::class);
    }

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
        return $this->managers()->wherePivot('fired_on', '!=', null);
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
        return $this->currentManagers()->where('manager_id', $manager->id)->exists();
    }

    /**
     * Returns the wrestler's current managers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function currentManagers()
    {
        return $this->managers()->wherePivot('fired_on', null);
    }

    /**
     * A wrestler hires a given manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return bool
     */
    public function hireManager($manager, $date)
    {
        if (!$manager->is_active) {
            throw new ModelIsInactiveException;
        }

        if ($this->hasManager($manager)) {
            throw new ModelHasManagerException;
        }

        return $this->managers()->attach($manager->id, ['hired_on' => $date]);
    }

    /**
     * A wrestler fires a given manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return bool
     */
    public function fireManager($manager, $date)
    {
        if (!$this->hasManager($manager)) {
            throw new ManagerNotHiredException;
        }

        return $this->managers()->updateExistingPivot($manager->id, ['fired_on' => $date]);
    }
}

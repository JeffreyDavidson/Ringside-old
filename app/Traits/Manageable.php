<?php

namespace App\Traits;

use App\Models\Roster\Manager;
use App\Exceptions\ManagerNotHiredException;
use App\Exceptions\ModelHasManagerException;
use App\Exceptions\ModelIsInactiveException;

trait Manageable
{
    /**
     * A model can have many managers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(Manager::class);
    }

    /**
     * Checks to see if the model has past managers.
     *
     * @return bool
     */
    public function hasPastManagers()
    {
        return $this->pastManagers()->exists();
    }

    /**
     * Returns all the past managers for a model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pastManagers()
    {
        return $this->managers()->wherePivot('fired_on', '!=', null);
    }

    /**
     * Checks to see if the model has any current managers.
     *
     * @return bool
     */
    public function hasCurrentManagers()
    {
        return $this->currentManagers->isNotEmpty();
    }

    /**
     * Checks to see if the model has a specific manager.
     *
     * @return bool
     */
    public function hasManager($manager)
    {
        return $this->currentManagers()->where('manager_id', $manager->id)->exists();
    }

    /**
     * Returns the model's current managers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentManagers()
    {
        return $this->managers()->wherePivot('fired_on', null);
    }

    /**
     * A model hires a given manager.
     *
     * @param  \App\Models\Roster\Manager  $manager
     * @return void
     *
     * @throws \App\Exceptions\ModelIsInactiveException
     * @throws \App\Exceptions\ModelHasManagerException
     */
    public function hireManager($manager, $date)
    {
        if (! $manager->isActive()) {
            throw new ModelIsInactiveException;
        }

        if ($this->hasManager($manager)) {
            throw new ModelHasManagerException;
        }

        $this->managers()->attach($manager->id, ['hired_on' => $date]);
    }

    /**
     * A model fires a given manager.
     *
     * @param  \App\Models\Roster\Manager  $manager
     * @return int
     *
     * @throws \App\Exceptions\ManagerNotHiredException
     */
    public function fireManager($manager, $date)
    {
        if (! $this->hasManager($manager)) {
            throw new ManagerNotHiredException;
        }

        $this->managers()->updateExistingPivot($manager->id, ['fired_on' => $date]);

        return $this;
    }
}

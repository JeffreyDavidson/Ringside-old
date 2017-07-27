<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasManagers {

	abstract public function managers();

	public function previousManagers()
	{
		return $this->managers()->wherePivot('fired_on', '<', Carbon::now());
	}

	public function currentManagers()
	{
		return $this->managers()->wherePivot('fired_on', '=', null);
	}

	public function hireManager($manager, $date = null)
	{
		return $this->managers()->attach($manager->id, ['hired_on' => $date ?: Carbon::now()]);
	}

	public function fireManager($manager, $date = null)
	{
		return $this->managers()->updateExistingPivot($manager->id, ['fired_on' => $date ?: Carbon::now()]);
	}
}
<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasTitles {

	abstract public function titles();

	public function hasTitle($title) {
        $this->load('titles');
	    return $this->titles->contains($title);
	}

	public function winTitle($title, $date = null)
	{
		if (! $date) {
			$date = Carbon::now();
		}

		if($this->hasTitle($title)) {
			return $this;
		}

		$this->titles()->create(['title_id' => $title->id, 'won_on' => $date]);

		return $this;
	}

	public function loseTitle($title, $date = null)
	{
	    if (! $date) {
			$date = Carbon::now();
		}

		if(! $this->hasTitle($title)) {
            return $this;
        }

		$this->titles()->whereTitleId($title->id)->whereNull('lost_on')->first()->loseTitle($date);

		return $this;
	}

	public function isChampionOfCurrentTitle() {
		//Do something

		return false;
	}
}
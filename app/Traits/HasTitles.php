<?php

namespace App\Traits;

use App\Title;
use Carbon\Carbon;

trait HasTitles {

	abstract public function titles();

	public function hasTitle($title) {
		if($title instanceof Title) {
			$title = $title->id;
		}

	    return $this->titles()->whereNull('lost_on')->get()->map(function($item) {
	    	return $item->title_id;
	    })->contains($title);
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
            return false;
        }

		$this->titles()->where('title_id', $title->id)->whereNull('lost_on')->first()->loseTitle($date);

		return true;
	}
}
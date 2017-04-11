<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $guarded = [];

    public function wrestlers()
    {
        return $this->belongsToMany(Wrestler::class)->withTimestamps();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function type()
    {
        return $this->belongsTo(MatchType::class, 'match_type_id');
    }

    public function titles()
    {
        return $this->belongsToMany(Title::class)->withTimestamps();
    }

    public function referees()
    {
        return $this->belongsToMany(Referee::class)->withTimestamps();
    }

    public function stipulations()
    {
        return $this->belongsToMany(Stipulation::class)->withTimestamps();
    }

	public function addWrestlers($wrestlers)
	{
		if($wrestlers instanceof Wrestler) {
			$wrestlers = collect([$wrestlers]);
		} else if(is_array($wrestlers) && $wrestlers[0] instanceof Wrestler) {
			$wrestlers = collect($wrestlers);
		}

		if($this->isTitleMatch()) {
			$wrestlers->each(function($wrestler) {
				if($wrestler->isChampionOfCurrentTitle()) {
					$this->addTitles($wrestler->currentTitles());
				}
			});
		}

		$this->wrestlers()->saveMany($wrestlers->all());
	}

    public function addTitles($titles)
    {
        if($titles instanceof Title) {
            $titles = collect([$titles]);
        } else if(is_array($titles) && $titles[0] instanceof Title) {
            $titles = collect($titles);
        }

        $this->titles()->saveMany($titles->all());
    }

    public function addStipulations($stipulations)
    {
        if($stipulations instanceof Stipulation) {
            $stipulations = collect([$stipulations]);
        } else if(is_array($stipulations) && $stipulations[0] instanceof Stipulation) {
            $stipulations = collect($stipulations);
        }

        $this->stipulations()->saveMany($stipulations->all());
    }

    public function addReferees($referees)
    {
        if($referees instanceof Referee) {
            $referees = collect([$referees]);
        } else if(is_array($referees) && $referees[0] instanceof Referee) {
            $referees = collect($referees);
        }

        $this->referees()->saveMany($referees->all());
    }

	public function isTitleMatch() {
		return $this->title_match;
    }
}

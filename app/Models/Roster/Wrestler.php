<?php

namespace App\Models\Roster;

use App\Models\Match;
use App\Interfaces\Competitor;
use App\Presenters\Roster\WrestlerPresenter;


class Wrestler extends RosterMember implements Competitor
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'hometown', 'height', 'weight', 'signature_move', 'is_active', 'hired_at'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = WrestlerPresenter::class;

    /**
     * Get all of the matches for the wrestler.
     */
    public function matches()
    {
        return $this->morphToMany(Match::class, 'competitor');
    }

    /**
     * Get all of the matches for the wrestler.
     */
    public function championships()
    {
        return $this->morphToMany(Championship::class, 'champion');
    }
}

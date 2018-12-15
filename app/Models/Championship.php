<?php

namespace App\Models;

use App\Models\Roster\TagTeam;
use App\Models\Roster\Wrestler;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'championships';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'won_on', 'lost_on',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['won_on', 'lost_on', 'successful_defenses'];

    /**
     * A championship can belong to many wrestlers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphedByMany
     */
    public function wrestlers()
    {
        return $this->morphedByMany(Wrestler::class, 'champion', 'championships');
    }

    /**
     * A championship can belong to many tag teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphedByMany
     */
    public function tagteams()
    {
        return $this->morphedByMany(TagTeam::class, 'champion', 'championships');
    }

    /**
     * A champion can be a wrestler or tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function champion()
    {
        return $this->morphTo();
    }

    /**
     * Formats won on date.
     *
     * @return string
     */
    public function wonOn()
    {
        return $this->model->won_on->format('F j, Y');
    }

    /**
     * Formats lost on date.
     *
     * @return string
     */
    public function lostOn()
    {
        return $this->model->lost_on ? $this->model->lost_on->format('F j, Y') : 'Present';
    }

    /**
     * Calculates how long a championship has been held.
     *
     * @return string
     */
    public function lengthOfReign()
    {
        return $this->model->lost_on ? $this->model->won_on->diffInDays($this->model->lost_on) . ' days' : 'Present';
    }
}

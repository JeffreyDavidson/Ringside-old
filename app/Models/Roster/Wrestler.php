<?php

namespace App\Models\Roster;

use App\Models\Match;
use App\Traits\Hireable;
use App\Traits\Injurable;
use App\Traits\Retirable;
use App\Traits\Manageable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use App\Models\Championship;
use App\Interfaces\Competitor;
use App\Traits\CompetitorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Wrestler extends Model implements Competitor
{
    use CompetitorTrait, Statusable, Injurable, Manageable, Hireable, Retirable, Suspendable, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'hired_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'hometown', 'height', 'weight', 'signature_move', 'is_active', 'hired_at'];

    /**
     * Get all of the matches for the wrestler.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function matches(): MorphToMany
    {
        return $this->morphToMany(Match::class, 'competitor');
    }

    /**
     * Get all of the championships held by the wrestler.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function championships(): MorphToMany
    {
        return $this->morphToMany(Championship::class, 'championships')->withPivot('id', 'won_on', 'lost_on', 'successful_defenses');
    }

    /**
     * Formats the wrestler's height.
     *
     * @return string
     */
    public function height()
    {
        $feet = floor($this->model->height / 12);
        $inches = ($this->model->height % 12);

        return $feet . '\'' . $inches . '"';
    }

    /**
     * Formats wrestler's height in feet.
     *
     * @return int
     */
    public function height_in_feet()
    {
        $feet = floor($this->model->height / 12);

        return (int)$feet;
    }

    /**
     * Formats wrestler's height in inches.
     *
     * @return int
     */
    public function height_in_inches()
    {
        $feet = floor($this->model->height / 12);
        $inches = ($this->model->height % 12);

        return $inches;
    }
}

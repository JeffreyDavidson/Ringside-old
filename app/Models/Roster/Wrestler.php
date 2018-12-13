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
use Laracodes\Presenter\Traits\Presentable;
use App\Presenters\Roster\WrestlerPresenter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Wrestler extends Model implements Competitor
{
    use CompetitorTrait, Statusable, Injurable, Manageable, Hireable, Retirable, Suspendable, Presentable, SoftDeletes;

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
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = WrestlerPresenter::class;

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
}

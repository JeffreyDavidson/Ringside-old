<?php

namespace App\Models;

use App\Models\Roster\TagTeam;
use App\Models\Roster\Wrestler;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\ChampionshipPresenter;
use Laracodes\Presenter\Traits\Presentable;

class Championship extends Model
{
    use Presentable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'championships';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'won_on' => 'datetime',
        'lost_on' => 'datetime',
    ];

    protected $fillable = ['won_on', 'lost_on', 'successful_defenses'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = ChampionshipPresenter::class;

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
}

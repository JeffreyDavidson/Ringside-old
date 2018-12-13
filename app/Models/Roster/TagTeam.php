<?php

namespace App\Models\Roster;

use Carbon\Carbon;
use App\Traits\Hireable;
use App\Traits\Retirable;
use App\Traits\Manageable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use App\Interfaces\Competitor;
use App\Traits\CompetitorTrait;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TagTeam extends Model implements Competitor
{
    use CompetitorTrait, Statusable, Retirable, Suspendable, Manageable, Hireable, SoftDeletes, Presentable;

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
    protected $fillable = ['name', 'slug', 'signature_move', 'is_active', 'hired_at'];

    /**
     * A tag can be have many wrestlers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wrestlers(): BelongsToMany
    {
        return $this->belongsToMany(Wrestler::class);
    }

    /**
     * Get all of the championships held by the wrestler.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function championships(): MorphToMany
    {
        return $this->morphToMany(Championship::class, 'champion')->withPivot('id', 'won_on', 'lost_on', 'successful_defenses');
    }

    /**
     * Get all of the wrestlers that are currently on the tag team.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCurrentWrestlersAttribute(): Collection
    {
        return $this->wrestlers()->wherePivot('left_on', null)->limit(2)->get();
    }

    /**
     * Attaches wrestlers to a tag team.
     *
     * @param  array $wrestlerIds
     * @param  array|null $current
     * @return $this
     */
    public function addWrestlers(array $wrestlerIds, array $current = null): TagTeam
    {
        collect($wrestlerIds)
            ->diff($current)
            ->each(function ($id) {
                $this->wrestlers()->attach($id, [
                    'joined_on' => Carbon::now(),
                ]);
            });

        return $this;
    }

    /**
     * Creates and attaches wrestlers to a tag team.
     *
     * @param  array $wrestlerIds
     * @return $this
     */
    public function syncWrestlers(array $wrestlerIds): TagTeam
    {
        $currentWrestlers = $this->wrestlers->modelKeys();

        $this->removeWrestlers($currentWrestlers, $wrestlerIds);

        $this->addWrestlers($wrestlerIds, $currentWrestlers);

        return $this;
    }

    public function removeWrestlers($currentWrestlers, $wrestlerIdsToRemove)
    {
        collect($currentWrestlers)
            ->diff($wrestlerIdsToRemove)
            ->each(function ($wrestlerId) {
                $this->wrestlers()->updateExistingPivot($wrestlerId, [
                    'left_on' => Carbon::now(),
                ]);
            });

        return $this;
    }
}

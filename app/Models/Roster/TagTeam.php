<?php

namespace App\Models\Roster;

use Carbon\Carbon;
use App\Interfaces\Competitor;

class TagTeam extends RosterMember implements Competitor
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'hometown', 'signature_move', 'is_active', 'hired_at'];

    /**
     * A tag can be have many wrestlers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wrestlers()
    {
        return $this->belongsToMany(Wrestler::class);
    }

    public function getCurrentWrestlersAttribute()
    {
        return $this->wrestlers()->wherePivot('left_on', null)->limit(2)->get();
    }

    /**
     * Attaches wrestlers to a tag team.
     *
     * @param  array $wrestlerIds
     * @return $this
     */
    public function addWrestlers(array $wrestlerIds)
    {
        foreach ($wrestlerIds as $wrestlerId) {
            $this->wrestlers()->attach($wrestlerId, ['joined_on' => Carbon::now()]);
        }

        return $this;
    }

    /**
     * Creates and attaches wrestlers to a tag team.
     *
     * @param  array $wrestlerIds
     * @return $this
     */
    public function syncWrestlers(array $wrestlerIds)
    {
        $currentTagTeamWrestlerIds = collect($this->wrestlers->modelKeys());
        $newTagTeamWrestlerIds = collect($wrestlerIds);

        $wrestlerIdsToAdd = $newTagTeamWrestlerIds->diff($currentTagTeamWrestlerIds);
        $wrestlerIdsToRemove = $currentTagTeamWrestlerIds->diff($newTagTeamWrestlerIds);

        foreach ($wrestlerIdsToRemove as $wrestlerId) {
            $this->wrestlers()->updateExistingPivot($wrestlerId, ['left_on' => Carbon::now()]);
        }

        foreach ($wrestlerIdsToAdd as $wrestlerId) {
            $this->wrestlers()->attach($wrestlerId, ['joined_on' => Carbon::now()]);
        }

        return $this;
    }
}

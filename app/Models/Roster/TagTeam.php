<?php

namespace App\Models\Roster;

use Carbon\Carbon;

class TagTeam extends RosterMember
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

    /**
     * Creates and attaches wrestlers to a tag team.
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
}

<?php

namespace App\Http\Controllers\Roster\TagTeam;

use Illuminate\Http\Request;
use App\Models\Roster\TagTeam;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roster\TagTeam\StoreTagTeamFormRequest;
use App\Http\Requests\Roster\TagTeam\UpdateTagTeamFormRequest;

class TagTeamsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TagTeam::class, 'tagteam');
    }

    /**
     * Show the form for creating a new tag team.
     *
     * @param  \App\Models\Roster\TagTeam  $tagteam
     * @return \Illuminate\View\View
     */
    public function create(TagTeam $tagteam) 
    {
        return view('tagteams.create', compact('tagteam'));
    }

    /**
     * Store a newly created tag team.
     *
     * @param  \App\Http\Requests\Roster\TagTeam\StoreTagTeamFormRequest  $request
     * @return \Illuminate\View\View
     */
    public function store(StoreTagTeamFormRequest $request)
    {
        $tagteam = TagTeam::create($request->all());
        $tagteam->addWrestlers($request->get('wrestlers'));

        if ($request->hired_at <= today()->toDateTimeString()) {
            return redirect()->route('active-tagteams.index');
        }

        return redirect()->route('inactive-tagteams.index');
    }

    public function edit(TagTeam $tagteam)
    {
        return view('tagteams.edit', compact('tagteam'));
    }

    public function update(UpdateTagTeamFormRequest $request, TagTeam $tagteam)
    {
        $tagteam->update($request->all());
        $tagteam->syncWrestlers($request->get('wrestlers'));

        if ($tagteam->isRetired()) {
            return redirect()->route('retired-tagteams.index');
        }

        if (!$tagteam->isActive()) {
            return redirect()->route('inactive-tagteams.index');
        }

        return redirect()->route('active-tagteams.index');
    }
}

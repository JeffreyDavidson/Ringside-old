<?php

namespace App\Http\Controllers\Roster\TagTeams;

use App\Models\TagTeam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagTeamsController extends Controller
{
    /** @var string */
    protected $authorizeResource = TagTeam::class;

    /**
     * Show the form for creating a new tag team.
     *
     * @param  \App\Models\TagTeam  $tagteam
     * @return \Illuminate\View\View
     */
    public function create(TagTeam $tagteam) 
    {
        return view('tagteams.create', compact('tagteam'));
    }
}

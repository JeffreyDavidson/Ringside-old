<?php

namespace App\Http\Controllers;

use App\RosterMember;
use Illuminate\Http\Request;

class RosterMembersController extends Controller
{

    public function show($id)
    {
        $rosterMember = RosterMember::findOrFail($id);
        return view('roster-members.show', ['rosterMember' => $rosterMember]);
    }
}

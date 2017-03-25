<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class WrestlersController extends Controller
{

    public function show($id)
    {
//        $wrestler = Wrestler::with('currentManagers', 'previousManagers', 'titles')->findOrFail($id);

        $wrestler = Wrestler::with('currentManagers', 'previousManagers', 'titles.title')->findOrFail($id);
        return view('wrestlers.show', ['wrestler' => $wrestler]);
    }
}

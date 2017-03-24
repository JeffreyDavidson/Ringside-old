<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class WrestlersController extends Controller
{

    public function show($id)
    {
        $wrestler = Wrestler::with('currentManagers', 'previousManagers', 'groupedTitles')->findOrFail($id);

        dd($wrestler);

        return view('wrestlers.show', ['wrestler' => $wrestler]);
    }
}

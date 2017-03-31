<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class WrestlersController extends Controller
{
    public function index()
    {
        $wrestlers = Wrestler::active()->get();

        return view('wrestlers.active', ['wrestlers' => $wrestlers]);
    }

    public function show($id)
    {
        $wrestler = Wrestler::with('currentManagers', 'previousManagers', 'titles.title', 'bio')->findOrFail($id);

        return view('wrestlers.show', ['wrestler' => $wrestler]);
    }

    public function edit(Wrestler $wrestler)
    {
        return view('wrestlers.edit', ['wrestler' => $wrestler]);
    }
}

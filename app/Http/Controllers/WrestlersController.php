<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class WrestlersController extends Controller
{
    public function index()
    {
        $wrestlers = Wrestler::all();

        return view('wrestlers.index', compact('wrestlers'));
    }

    public function create()
    {
        return view('wrestlers.create', ['wrestler' => new Wrestler]);
    }

    public function show($id)
    {
        $wrestler = Wrestler::with('currentManagers', 'previousManagers', 'titles.title', 'bio')->findOrFail($id);

        return view('wrestlers.show', compact('wrestler'));
    }

    public function edit(Wrestler $wrestler)
    {
        return view('wrestlers.edit', compact('wrestler'));
    }
}

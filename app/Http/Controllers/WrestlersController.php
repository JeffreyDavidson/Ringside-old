<?php

namespace App\Http\Controllers;

use App\Models\Wrestler;
use Illuminate\Http\Request;

class WrestlersController extends Controller
{
    /**
     * Display a listing of all the titles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wrestlers = Wrestler::all();

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($wrestlers);
        }

        return response()->view('wrestlers.index', ['wrestlers' => $wrestlers]);
    }

    /**
     * Show the form for creating a new wrestler.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('wrestlers.create', ['wrestler' => new Wrestler]);
    }

    /**
     * Display the specified wrestler.
     *
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function show(Wrestler $wrestler)
    {
        $wrestler->load('currentManagers', 'previousManagers', 'titles.title', 'bio');

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($wrestler);
        }

        return response()->view('wrestlers.show', ['wrestler' => $wrestler]);
    }

    /**
     * Show the form for editing a title.
     *
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function edit(Wrestler $wrestler)
    {
        return response()->view('wrestlers.edit', ['wrestler' => $wrestler]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\WrestlerCreateFormRequest;
use App\Http\Requests\WrestlerEditFormRequest;
use App\Models\Wrestler;

class WrestlersController extends Controller
{
    /**
     * Display a listing of all the wrestlers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wrestlers = Wrestler::all();

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
     * Store a newly created wrestler.
     *
     * @param WrestlerCreateFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(WrestlerCreateFormRequest $request)
    {
        Wrestler::create([
            'name' => request('name'),
            'slug' => request('slug'),
            'status_id' => request('status_id'),
            'hired_at' => request('hired_at'),
        ]);

        return redirect(route('wrestlers.index'));
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

        return response()->view('wrestlers.show', ['wrestler' => $wrestler]);
    }

    /**
     * Show the form for editing a wrestler.
     *
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function edit(Wrestler $wrestler)
    {
        return response()->view('wrestlers.edit', ['wrestler' => $wrestler]);
    }

    /**
     * Update the specified wrestler.
     *
     * @param WrestlerEditFormRequest $request
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function update(WrestlerEditFormRequest $request, Wrestler $wrestler)
    {
        $wrestler->update([
            'name' => request('name'),
            'slug' => request('slug'),
            'status_id' => request('status_id'),
            'hired_at' => request('hired_at')
        ]);

        $wrestler->bio()->update([
            'hometown' => request('hometown'),
            'height' => request('feet') * 12 + request('inches'),
            'weight' => request('weight'),
            'signature_move' => request('signature_move'),
        ]);

        return redirect(route('wrestlers.index'));
    }

    /**
     * Delete the specified wrestler.
     *
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wrestler $wrestler)
    {
        $wrestler->delete();

        return redirect(route('wrestlers.index'));
    }
}

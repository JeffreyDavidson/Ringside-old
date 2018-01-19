<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use App\Http\Requests\WrestlerEditFormRequest;
use App\Http\Requests\WrestlerCreateFormRequest;

class WrestlersController extends Controller
{
    /**
     * Display a listing of all the wrestlers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Wrestler::class);

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
        $this->authorize('create', Wrestler::class);

        $statuses = WrestlerStatus::available();

        return response()->view('wrestlers.create', ['wrestler' => new Wrestler, 'statuses' => $statuses]);
    }

    /**
     * Store a newly created wrestler.
     *
     * @param WrestlerCreateFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(WrestlerCreateFormRequest $request)
    {
        $this->authorize('create', Wrestler::class);

        Wrestler::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'status_id' => $request->status_id,
            'hired_at' => Carbon::parse($request->hired_at),
            'hometown' => $request->hometown,
            'height' => ($request->feet * 12) + $request->inches,
            'weight' => $request->weight,
            'signature_move' => $request->signature_move,
        ]);

        return redirect()->route('wrestlers.index');
    }

    /**
     * Display the specified wrestler.
     *
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function show(Wrestler $wrestler)
    {
        $this->authorize('show', Wrestler::class);

        $wrestler->load('currentManagers', 'pastManagers', 'currentTitlesHeld', 'pastTitlesHeld');

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
        $this->authorize('edit', Wrestler::class);

        $statuses = WrestlerStatus::available($wrestler->status());

        return response()->view('wrestlers.edit', ['wrestler' => $wrestler, 'statuses' => $statuses]);
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
        $this->authorize('edit', Wrestler::class);

        /* TODO: Figure out if I should do a Rule or how I should handle the change better from going from status to status. */
        //if ($wrestler->status() != $request->status_id) {
        //    if ($wrestler->status() == WrestlerStatus::RETIRED) {
        //        $wrestler->unretire();
        //    } else if ($wrestler->status() == WrestlerStatus::INJURED) {
        //        $wrestler->heal();
        //    } else if ($wrestler->status() == WrestlerStatus::SUSPENDED) {
        //        $wrestler->rejoin();
        //    }
        //}

        $wrestler->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'status_id' => $request->status_id,
            'hired_at' => $request->hired_at,
            'hometown' => $request->hometown,
            'height' => ($request->feet * 12) + $request->inches,
            'weight' => $request->weight,
            'signature_move' => $request->signature_move,
        ]);

        //if ($request->status_id == WrestlerStatus::INJURED) {
        //    $wrestler->injure();
        //} else if ($request->status_id == WrestlerStatus::SUSPENDED) {
        //    $wrestler->suspend();
        //} else if ($request->status_id == WrestlerStatus::RETIRED) {
        //    $wrestler->retire();
        //}

        return redirect()->route('wrestlers.index');
    }

    /**
     * Delete the specified wrestler.
     *
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wrestler $wrestler)
    {
        $this->authorize('delete', Wrestler::class);

        $wrestler->delete();

        return redirect()->route('wrestlers.index');
    }
}

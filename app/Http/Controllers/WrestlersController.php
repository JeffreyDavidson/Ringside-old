<?php

namespace App\Http\Controllers;

use App\Events\WrestlerStatusChanged;
use App\Http\Requests\WrestlerCreateFormRequest;
use App\Http\Requests\WrestlerEditFormRequest;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Carbon\Carbon;

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

        $wrestler->load('currentManagers', 'previousManagers', 'currentTitlesHeld', 'previousTitlesHeld');

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


        if ($wrestler->status() != $request->status_id) {
            $wrestler->statusChanged();
        }

        if ($request->status_id == WrestlerStatus::INJURED) {
            $wrestler->injure();
        } else if ($request->status_id == WrestlerStatus::SUSPENDED) {
            $wrestler->suspend();
        } else if ($request->status_id == WrestlerStatus::RETIRED) {
            $wrestler->retire();
        }

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

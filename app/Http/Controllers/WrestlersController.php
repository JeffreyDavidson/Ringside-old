<?php

namespace App\Http\Controllers;

use App\Http\Requests\WrestlerCreateFormRequest;
use App\Http\Requests\WrestlerEditFormRequest;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;

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
        $statuses = WrestlerStatus::whereIn('name', ['Active', 'Inactive'])->get();

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
        Wrestler::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'status_id' => $request->status_id,
            'hired_at' => $request->hired_at,
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
        if ($wrestler->status() != $request->status_id) {
            if ($wrestler->status() == WrestlerStatus::RETIRED) {
                $wrestler->unretire();
            } else if ($wrestler->status() == WrestlerStatus::INJURED) {
                $wrestler->heal();
            } else if ($wrestler->status() == WrestlerStatus::SUSPENDED) {
                $wrestler->rejoin();
            }
        }

        $wrestler->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'status_id' => $request->status_id,
            'hired_at' => $request->hired_at,
        ]);

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
        $wrestler->delete();

        return redirect()->route('wrestlers.index');
    }
}

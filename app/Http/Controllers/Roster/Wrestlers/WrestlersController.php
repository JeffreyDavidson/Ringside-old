<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Models\Wrestler;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWrestlerFormRequest;
use App\Http\Requests\UpdateWrestlerFormRequest;

class WrestlersController extends Controller
{
    /** @var string */
    protected $authorizeResource = Wrestler::class;

    /**
     * Show the form for creating a new wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\View\View
     */
    public function create(Wrestler $wrestler)
    {
        return view('wrestlers.create', compact('wrestler'));
    }

    /**
     * Store a newly created wrestler.
     *
     * @param  \App\Http\Requests\StoreWrestlerFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreWrestlerFormRequest $request)
    {
        Wrestler::create($request->all());

        if ($request->hired_at <= today()->toDateTimeString()) {
            return redirect()->route('active-wrestlers.index');
        }

        return redirect()->route('inactive-wrestlers.index');
    }

    /**
     * Display the specified wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\View\View
     */
    public function show(Wrestler $wrestler)
    {
        $wrestler->load('matches');

        return view('wrestlers.show', compact('wrestler'));
    }

    /**
     * Show the form for editing a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\View\View
     */
    public function edit(Wrestler $wrestler)
    {
        return view('wrestlers.edit', compact('wrestler'));
    }

    /**
     * Update the specified wrestler.
     *
     * @param  \App\Http\Requests\UpdateWrestlerFormRequest  $request
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateWrestlerFormRequest $request, Wrestler $wrestler)
    {
        $wrestler->update($request->all());

        if ($wrestler->isRetired()) {
            return redirect()->route('retired-wrestlers.index');
        }

        if (! $wrestler->isActive()) {
            return redirect()->route('inactive-wrestlers.index');
        }

        return redirect()->route('active-wrestlers.index');
    }

    /**
     * Delete the specified wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $wrestler->delete();

        return redirect()->back();
    }
}

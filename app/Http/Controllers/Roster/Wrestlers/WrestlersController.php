<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Models\Wrestler;
use App\Http\Controllers\Controller;
use App\Http\Requests\WrestlerEditFormRequest;
use App\Http\Requests\WrestlerCreateFormRequest;

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
     * @param  \App\Http\Requests\WrestlerCreateFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(WrestlerCreateFormRequest $request)
    {
        Wrestler::create($request->all());

        return redirect()->route('active-wrestlers.index');
    }

    /**
     * Display the specified wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\View\View
     */
    public function show(Wrestler $wrestler)
    {
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
     * @param  \App\Http\Requests\WrestlerEditFormRequest  $request
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(WrestlerEditFormRequest $request, Wrestler $wrestler)
    {
        $wrestler->update($request->all());

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

        return redirect()->route('active-wrestlers.index');
    }
}

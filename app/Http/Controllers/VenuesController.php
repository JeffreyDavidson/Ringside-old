<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Http\Requests\VenueEditFormRequest;
use App\Http\Requests\VenueCreateFormRequest;

class VenuesController extends Controller
{
    /** @var string */
    protected $authorizeResource = Venue::class;

    /**
     * Display a listing of all the venues.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $venues = Venue::paginate(10);

        return view('venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\View\View
     */
    public function create(Venue $venue)
    {
        return view('venues.create', compact('venue'));
    }

    /**
     * Store a newly created venue.
     *
     * @param  \App\Http\Requets\VenueCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VenueCreateFormRequest $request)
    {
        Venue::create($request->all());

        return redirect()->route('venues.index');
    }

    /**
     * Display the specified venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\View\View
     */
    public function show(Venue $venue)
    {
        return view('venues.show', compact('venue'));
    }

    /**
     * Show the form for editing an venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\View\View
     */
    public function edit(Venue $venue)
    {
        return view('venues.edit', compact('venue'));
    }

    /**
     * Update the specified venue.
     *
     * @param  \App\Http\Requests\VenueEditFormRequest  $request
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(VenueEditFormRequest $request, Venue $venue)
    {
        $venue->update($request->all());

        return redirect()->route('venues.index');
    }

    /**
     * Delete the specified venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();

        return redirect()->route('venues.index');
    }
}

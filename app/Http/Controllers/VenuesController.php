<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Http\Requests\VenueEditFormRequest;
use App\Http\Requests\VenueCreateFormRequest;

class VenuesController extends Controller
{
    /**
     * Display a listing of all the venues.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Venue::class);

        $venues = Venue::paginate(10);

        return response()->view('venues.index', ['venues' => $venues]);
    }

    /**
     * Show the form for creating a new venue.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Venue::class);

        return response()->view('venues.create', ['venue' => new Venue]);
    }

    /**
     * Store a newly created venue.
     *
     * @param  \App\Http\Requets\VenueCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VenueCreateFormRequest $request)
    {
        $this->authorize('create', Venue::class);

        Venue::create($request->only('name', 'address', 'city', 'state', 'postcode'));

        return redirect()->route('venues.index');
    }

    /**
     * Display the specified venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        $this->authorize('show', Venue::class);

        return response()->view('venues.show', ['venue' => $venue]);
    }

    /**
     * Show the form for editing an venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        $this->authorize('edit', Venue::class);

        return response()->view('venues.edit', ['venue' => $venue]);
    }

    /**
     * Update the specified venue.
     *
     * @param  \App\Http\Requests\VenueEditFormRequest  $request
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function update(VenueEditFormRequest $request, Venue $venue)
    {
        $this->authorize('edit', Venue::class);

        $venue->update($request->only('name', 'address', 'city', 'state', 'postcode'));

        return redirect()->route('venues.index');
    }

    /**
     * Delete the specified venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        $this->authorize('delete', Venue::class);

        $venue->delete();

        return redirect()->route('venues.index');
    }
}

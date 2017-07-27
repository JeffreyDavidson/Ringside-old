<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenueCreateFormRequest;
use App\Http\Requests\VenueEditFormRequest;
use App\Models\Venue;

class VenuesController extends Controller
{
    /**
     * Display a listing of all the venues.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $venues = Venue::all();

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
     * @param VenueCreateFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VenueCreateFormRequest $request)
    {
        Venue::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
        ]);

        return redirect()->route('venues.index');
    }

    /**
     * Display the specified venue.
     *
     * @param  Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        return response()->view('venues.show', ['venue' => $venue]);
    }

    /**
     * Show the form for editing an venue.
     *
     * @param  Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        return response()->view('venues.edit', ['venue' => $venue]);
    }

    /**
     * Update the specified venue.
     *
     * @param VenueEditFormRequest $request
     * @param  Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function update(VenueEditFormRequest $request, Venue $venue)
    {
        $venue->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
        ]);

        return redirect()->route('venues.index');
    }

    /**
     * Delete the specified venue.
     *
     * @param  Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();

        return redirect()->route('venues.index');
    }
}

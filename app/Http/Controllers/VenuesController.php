<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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
        return response()->view('venues.create', ['venue' => new Venue]);
    }

    /**
     * Store a newly created venue.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:venues,name',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required|not_in:0',
            'postcode' => 'required|numeric|digits:5'
        ]);

        Venue::create([
            'name' => request('name'),
            'address' => request('address'),
            'city' => request('city'),
            'state' => request('state'),
            'postcode' => request('postcode'),
        ]);

        return redirect(route('venues.index'));
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
     * @param  \Illuminate\Http\Request  $request
     * @param  Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venue $venue)
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique('venues' ,'name')->ignore($venue->id)],
            'address' => 'required',
            'city' => 'required',
            'state' => 'required|not_in:0',
            'postcode' => 'required|digits:5'
        ]);

        $venue->update([
            'name' => request('name'),
            'address' => request('address'),
            'city' => request('city'),
            'state' => request('state'),
            'postcode' => request('postcode')
        ]);

        return redirect(route('venues.index'));
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

        return redirect(route('venues.index'));
    }
}

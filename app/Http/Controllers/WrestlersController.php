<?php

namespace App\Http\Controllers;

use App\Models\Wrestler;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WrestlersController extends Controller
{
    /**
     * Display a listing of all the wrstlers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wrestlers = Wrestler::all();

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($wrestlers);
        }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:wrestlers,name',
            'slug' => 'required|unique:wrestlers,slug',
            'status_id' => 'required|integer|exists:wrestler_statuses,id',
            'hometown' => 'required',
            'feet' => 'required|integer',
            'inches' => 'required|integer',
            'weight' => 'required|integer',
            'signature_move' => 'required',
            'hired_at' => 'required|date',
        ]);

        $wrestler = Wrestler::create([
            'name' => request('name'),
            'slug' => request('slug'),
            'status_id' => request('status_id'),
            'hired_at' => request('hired_at'),
        ]);

        $wrestler->bio()->create([
            'hometown' => request('hometown'),
            'height' => request('feet') * 12 + request('inches'),
            'weight' => request('weight'),
            'signature_move' => request('signature_move'),
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($wrestler);
        }

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

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($wrestler);
        }

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
     * @param  \Illuminate\Http\Request  $request
     * @param  Wrestler $wrestler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wrestler $wrestler)
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique('wrestlers' ,'name')->ignore($wrestler->id)],
            'slug' => ['required', Rule::unique('wrestlers' ,'slug')->ignore($wrestler->id)],
            'status_id' => 'required',
            'hometown' => 'required',
            'feet' => 'required|integer',
            'inches' => 'required|integer',
            'weight' => 'required|integer',
            'signature_move' => 'required',
            'hired_at' => 'required|date',
        ]);

        if ($wrestler->matches->count() > 0)
        {
            dd($wrestler->matches);
            $this->validate($request, [
                'hired_at' => 'before_or_equal:'.$wrestler->matches->first()->event->date,
            ]);
        }

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

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($wrestler);
        }

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

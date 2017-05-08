<?php

namespace App\Http\Controllers;

use App\Models\Wrestler;
use Illuminate\Http\Request;

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
            'name' => 'required|unique:arenas,name',
            'slug' => 'required',
            'status_id' => 'required'
        ]);

        $wrestler = Wrestler::create([
            'name' => request('name'),
            'slug' => request('slug'),
            'status_id' => request('status_id')
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
     * Show the form for editing a title.
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
            'status_id' => 'required'
        ]);

        $title->update([
            'name' => request('name'),
            'slug' => request('slug'),
            'status_Zid' => request('status_id')
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

<?php

namespace App\Http\Controllers;

use App\Models\Arena;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ArenasController extends Controller
{
    /**
     * Display a listing of all the arenas.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arenas = Arena::all();

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($arenas);
        }

        return response()->view('arenas.index', ['arenas' => $arenas]);
    }

    /**
     * Show the form for creating a new arena.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('arenas.create', ['arena' => new Arena]);
    }

    /**
     * Store a newly created arena.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:arenas,name',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postcode' => 'required|numeric|digits:5'
        ]);

        $arena = Arena::create([
            'name' => request('name'),
            'address' => request('address'),
            'city' => request('city'),
            'state' => request('state'),
            'postcode' => request('postcode'),
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($arena);
        }

        return redirect(route('arenas.index'));
    }

    /**
     * Display the specified arena.
     *
     * @param  Arena $arena
     * @return \Illuminate\Http\Response
     */
    public function show(Arena $arena)
    {
        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($arena);
        }

        return response()->view('arenas.show', ['arena' => $arena]);
    }

    /**
     * Show the form for editing an arena.
     *
     * @param  Arena $arena
     * @return \Illuminate\Http\Response
     */
    public function edit(Arena $arena)
    {
        return response()->view('arenas.edit', ['arena' => $arena]);
    }

    /**
     * Update the specified arena.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Arena $arena
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arena $arena)
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique('stipulations' ,'name')->ignore($arena->id)],
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postcode' => 'required|digits:5'
        ]);

        $arena->update([
            'name' => request('name'),
            'address' => request('address'),
            'city' => request('city'),
            'state' => request('state'),
            'postcode' => request('postcode')
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($arena);
        }

        return redirect(route('arenas.index'));
    }

    /**
     * Delete the specified arena.
     *
     * @param  Arena $arena
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arena $arena)
    {
        $arena->delete();

        return redirect(route('arenas.index'));
    }
}

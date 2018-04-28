<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Wrestler;
use App\Http\Requests\WrestlerEditFormRequest;
use App\Http\Requests\WrestlerCreateFormRequest;

class WrestlersController extends Controller
{
    /**
     * Display a listing of all the wrestlers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Wrestler::class);

        $wrestlers = Wrestler::with('status')->paginate(10);

        return response()->view('wrestlers.index', ['wrestlers' => $wrestlers]);
    }

    /**
     * Show the form for creating a new wrestler.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Wrestler::class);

        return response()->view('wrestlers.create', ['wrestler' => new Wrestler]);
    }

    /**
     * Store a newly created wrestler.
     *
     * @param WrestlerCreateFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(WrestlerCreateFormRequest $request)
    {
        $this->authorize('create', Wrestler::class);

        Wrestler::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'status_id' => $request->input('status_id'),
            'hired_at' => Carbon::parse($request->input('hired_at')),
            'hometown' => $request->input('hometown'),
            'height' => ($request->input('feet') * 12) + $request->input('inches'),
            'weight' => $request->input('weight'),
            'signature_move' => $request->input('signature_move'),
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
        $this->authorize('show', Wrestler::class);

        $wrestler->load('currentManagers', 'pastManagers', 'currentTitlesHeld', 'pastTitlesHeld');

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
        $this->authorize('edit', Wrestler::class);

        return response()->view('wrestlers.edit', ['wrestler' => $wrestler]);
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
        $this->authorize('edit', Wrestler::class);

        $wrestler->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'status_id' => $request->input('status_id'),
            'hired_at' => $request->input('hired_at'),
            'hometown' => $request->input('hometown'),
            'height' => ($request->input('feet') * 12) + $request->input('inches'),
            'weight' => $request->input('weight'),
            'signature_move' => $request->input('signature_move'),
        ]);

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
        $this->authorize('delete', Wrestler::class);

        $wrestler->delete();

        return redirect()->route('wrestlers.index');
    }
}

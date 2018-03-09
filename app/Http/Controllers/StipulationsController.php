<?php

namespace App\Http\Controllers;

use App\Models\Stipulation;
use App\Http\Requests\StipulationEditFormRequest;
use App\Http\Requests\StipulationCreateFormRequest;

class StipulationsController extends Controller
{
    /**
     * Display a listing of all the stipulations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Stipulation::class);

        $stipulations = Stipulation::all();

        return response()->view('stipulations.index', ['stipulations' => $stipulations]);
    }

    /**
     * Show the form for creating a new stipulation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Stipulation::class);

        return response()->view('stipulations.create', ['stipulation' => new Stipulation]);
    }

    /**
     * Store a newly created stipulation.
     *
     * @param StipulationCreateFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StipulationCreateFormRequest $request)
    {
        $this->authorize('create', Stipulation::class);

        Stipulation::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('stipulations.index');
    }

    /**
     * Display the specified stipulation.
     *
     * @param  Stipulation $stipulation
     * @return \Illuminate\Http\Response
     */
    public function show(Stipulation $stipulation)
    {
        $this->authorize('show', Stipulation::class);

        return response()->view('stipulations.show', ['stipulation' => $stipulation]);
    }

    /**
     * Show the form for editing a stipulation.
     *
     * @param  Stipulation  $stipulation
     * @return \Illuminate\Http\Response
     */
    public function edit(Stipulation $stipulation)
    {
        $this->authorize('edit', Stipulation::class);

        return response()->view('stipulations.edit', ['stipulation' => $stipulation]);
    }

    /**
     * Update the specified stipulation.
     *
     * @param StipulationEditFormRequest $request
     * @param  Stipulation $stipulation
     * @return \Illuminate\Http\Response
     */
    public function update(StipulationEditFormRequest $request, Stipulation $stipulation)
    {
        $this->authorize('edit', Stipulation::class);

        $stipulation->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('stipulations.index');
    }

    /**
     * Delete the specified stipulation.
     *
     * @param  Stipulation $stipulation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stipulation $stipulation)
    {
        $this->authorize('delete', Stipulation::class);

        $stipulation->delete();

        return redirect()->route('stipulations.index');
    }
}

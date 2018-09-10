<?php

namespace App\Http\Controllers;

use App\Models\Stipulation;
use App\Http\Requests\StipulationEditFormRequest;
use App\Http\Requests\StipulationCreateFormRequest;

class StipulationsController extends Controller
{
    /** @var string */
    protected $authorizeResource = Stipulation::class;

    /**
     * Display a listing of all the stipulations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stipulations = Stipulation::paginate(10);

        return view('stipulations.index', compact('stipulations'));
    }

    /**
     * Show the form for creating a new stipulation.
     *
     * @param  \App\Models\Stipulation  $stipulation
     * @return \Illuminate\View\View
     */
    public function create(Stipulation $stipulation)
    {
        return view('stipulations.create', compact('stipulation'));
    }

    /**
     * Store a newly created stipulation.
     *
     * @param  App\Http\Requests\StipulationCreateFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StipulationCreateFormRequest $request)
    {
        Stipulation::create($request->all());

        return redirect()->route('stipulations.index');
    }

    /**
     * Display the specified stipulation.
     *
     * @param  \App\Models\Stipulation  $stipulation
     * @return \Illuminate\View\View
     */
    public function show(Stipulation $stipulation)
    {
        return view('stipulations.show', compact('stipulation'));
    }

    /**
     * Show the form for editing a stipulation.
     *
     * @param  \App\Models\Stipulation  $stipulation
     * @return \Illuminate\View\View
     */
    public function edit(Stipulation $stipulation)
    {
        return view('stipulations.edit', compact('stipulation'));
    }

    /**
     * Update the specified stipulation.
     *
     * @param  \App\Http\Requests\StipulationEditFormRequest  $request
     * @param  \App\Models\Stipulation  $stipulation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StipulationEditFormRequest $request, Stipulation $stipulation)
    {
        $stipulation->update($request->all());

        return redirect()->route('stipulations.index');
    }

    /**
     * Delete the specified stipulation.
     *
     * @param  \App\Models\Stipulation  $stipulation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Stipulation $stipulation)
    {
        $stipulation->delete();

        return redirect()->route('stipulations.index');
    }
}

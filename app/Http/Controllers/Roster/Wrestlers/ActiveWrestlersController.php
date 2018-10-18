<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class ActiveWrestlersController extends Controller
{
    /** @var string */
    protected $authorizeResource = Wrestler::class;

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'index',
            'store' => 'activate',
            'destroy' => 'deactivate',
        ];
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
    }

    /**
     * Display a listing of all active wrestlers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wrestlers = Wrestler::active()->paginate(10);

        return view('wrestlers.active', compact('wrestlers'));
    }

    /**
     * Active an inactive wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Wrestler $wrestler)
    {
        $wrestler->activate();

        return redirect()->route('inactive-wrestlers.index');
    }

    /**
     * Deactivates an active wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $wrestler->deactivate();

        return redirect()->route('active-wrestlers.index');
    }
}

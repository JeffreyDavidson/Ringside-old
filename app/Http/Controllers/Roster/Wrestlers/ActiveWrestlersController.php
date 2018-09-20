<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Models\Wrestler;
use App\Http\Controllers\Controller;

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
     * Store a newly created active wrestler.
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

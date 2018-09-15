<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Models\Wrestler;
use App\Http\Controllers\Controller;

class RetiredWrestlersController extends Controller
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
            'store' => 'retire',
            'destroy' => 'unretire',
        ];
    }

    /**
     * Display a listing of all retired wrestlers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wrestlers = Wrestler::retired()->paginate(10);

        return view('wrestlers.retired', compact('wrestlers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Wrestler $wrestler)
    {
        dd($wrestler);
        $wrestler->retire();

        return redirect()->route('wrestlers.index');
    }

    /**
     * Activate a retired wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $wrestler->unretire();

        return redirect()->route('wrestlers.index');
    }
}

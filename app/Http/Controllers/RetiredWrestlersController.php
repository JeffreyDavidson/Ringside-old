<?php

namespace App\Http\Controllers;

use App\Models\Wrestler;

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
            'destroy' => 'activate',
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Wrestler $wrestler)
    {
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
        $wrestler->activate();

        return redirect()->route('wrestlers.index');
    }
}

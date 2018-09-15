<?php

namespace App\Http\Controllers\Titles;

use App\Models\Title;
use App\Http\Controllers\Controller;

class RetiredTitlesController extends Controller
{
    /** @var string */
    protected $authorizeResource = Title::class;

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
     * Store a newly created retired title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Title $title)
    {
        $title->retire();

        return redirect()->route('titles.index');
    }

    /**
     * Activate a retired title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Title $title)
    {
        $title->activate();

        return redirect()->route('titles.index');
    }
}

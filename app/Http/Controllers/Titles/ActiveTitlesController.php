<?php

namespace App\Http\Controllers\Titles;

use App\Http\Controllers\Controller;
use App\Models\Title;

class ActiveTitlesController extends Controller
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
     * Display a listing of all active titles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $titles = Title::active()->paginate(10);

        return view('titles.active', compact('titles'));
    }

    /**
     * Activate an inactive title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Title $title)
    {
        $title->activate();

        return redirect()->route('inactive-titles.index');
    }

    /**
     * Deactivates an active title.
     *
     * @param  \App\Models\Title $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Title $title)
    {
        $title->deactivate();

        return redirect()->route('active-titles.index');
    }
}

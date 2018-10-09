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
            'index' => 'index',
            'store' => 'retire',
            'destroy' => 'unretire',
        ];
    }

    /**
     * Display a listing of all retired titles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $titles = Title::retired()->paginate(10);

        return view('titles.retired', compact('titles'));
    }

    /**
     * Retire a title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Title $title)
    {
        $title->retire();

        return redirect()->back();
    }

    /**
     * Unretire a retired title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Title $title)
    {
        $title->unretire();

        return redirect()->back();
    }
}

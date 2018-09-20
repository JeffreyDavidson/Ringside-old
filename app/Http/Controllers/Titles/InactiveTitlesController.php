<?php

namespace App\Http\Controllers\Titles;

use App\Models\Title;
use App\Http\Controllers\Controller;

class InactiveTitlesController extends Controller
{
    /** @var string */
    protected $authorizeResource = Title::class;

    /**
     * Display a listing of all inactive titles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $titles = Title::inactive()->paginate(10);

        return view('titles.inactive', compact('titles'));
    }
}

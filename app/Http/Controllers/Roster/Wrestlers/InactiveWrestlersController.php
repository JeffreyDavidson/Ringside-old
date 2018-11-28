<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Models\Wrestler;
use App\Http\Controllers\Controller;

class InactiveWrestlersController extends Controller
{
    /** @var string */
    protected $authorizeResource = Wrestler::class;

    /**
     * Display a listing of all inactive wrestlers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wrestlers = Wrestler::inactive()->paginate(10);

        return view('wrestlers.inactive', compact('wrestlers'));
    }
}

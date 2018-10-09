<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class InactiveWrestlersController extends Controller
{
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

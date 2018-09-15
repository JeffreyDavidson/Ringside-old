<?php

namespace App\Http\Controllers\Roster\Wrestlers;

use App\Models\Wrestler;
use App\Http\Controllers\Controller;

class ActiveWrestlersController extends Controller
{
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

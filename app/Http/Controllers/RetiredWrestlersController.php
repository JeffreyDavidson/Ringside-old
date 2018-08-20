<?php

namespace App\Http\Controllers;

use App\Models\Wrestler;
use Illuminate\Http\Request;

class RetiredWrestlersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('retire', Wrestler::class);

        $wrestler = Wrestler::findOrFail($request->input('wrestler_id'));

        $wrestler->retire();

        return redirect()->route('wrestlers.index');
    }
}

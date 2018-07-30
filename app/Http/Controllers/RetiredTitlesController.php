<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Title;

class RetiredTitlesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('retire', Title::class);

        $title = Title::findOrFail($request->input('title_id'));

        $title->retire();

        return redirect()->route('titles.index');
    }
}

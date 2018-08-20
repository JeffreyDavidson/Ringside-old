<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;

class RetiredTitlesController extends Controller
{
    /**
     * Store a newly created retired title.
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

    /**
     * Store a newly created retired title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Title $title)
    {
        $this->authorize('unretire', Title::class);

        $title->unretire();

        return redirect()->route('titles.index');
    }
}

<?php

namespace App\Http\Controllers\Titles;

use Carbon\Carbon;
use App\Models\Title;
use App\Http\Controllers\Controller;
use App\Http\Requests\TitleEditFormRequest;
use App\Http\Requests\TitleCreateFormRequest;

class TitlesController extends Controller
{
    /** @var string */
    protected $authorizeResource = Title::class;

    /**
     * Show the form for creating a new title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\View\View
     */
    public function create(Title $title)
    {
        return view('titles.create', compact('title'));
    }

    /**
     * Store a newly created title.
     *
     * @param  \App\Http\Requests\TitleCreateFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TitleCreateFormRequest $request)
    {
        Title::create($request->all());

        if ($request->introduced_at <= Carbon::today()->toDateTimeString()) {
            return redirect()->route('active-titles.index');
        }

        return redirect()->route('inactive-titles.index');
    }

    /**
     * Display the specified title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\View\View
     */
    public function show(Title $title)
    {
        return view('titles.show', compact('title'));
    }

    /**
     * Show the form for editing a title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\View\View
     */
    public function edit(Title $title)
    {
        return view('titles.edit', compact('title'));
    }

    /**
     * Update the specified title.
     *
     * @param  \App\Http\Requests\TitleEditFormRequest  $request
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TitleEditFormRequest $request, Title $title)
    {
        $title->update($request->all());

        if ($request->introduced_at > Carbon::today()->toDateTimeString() || !$title->isActive()) {
            return redirect()->route('inactive-titles.index');
        } elseif ($title->isRetired()) {
            return redirect()->route('retired-titles.index');
        }

        return redirect()->route('inactive-titles.index');
    }

    /**
     * Delete the specified title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Title $title)
    {
        $title->delete();

        return redirect()->back();
    }
}

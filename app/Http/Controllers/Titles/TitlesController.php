<?php

namespace App\Http\Controllers\Titles;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTitleFormRequest;
use App\Http\Requests\UpdateTitleFormRequest;
use App\Models\Title;

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
     * @param  \App\Http\Requests\StoreTitleFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTitleFormRequest $request)
    {
        Title::create($request->all());

        if ($request->introduced_at <= today()->toDateTimeString()) {
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
     * @param  \App\Http\Requests\UpdateTitleFormRequest  $request
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTitleFormRequest $request, Title $title)
    {
        $title->update($request->all());

        if ($title->isRetired()) {
            return redirect()->route('retired-titles.index');
        }

        if (!$title->isActive()) {
            return redirect()->route('inactive-titles.index');
        }

        return redirect()->route('active-titles.index');
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

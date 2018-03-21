<?php

namespace App\Http\Controllers;

use App\Models\Title;
use App\Http\Requests\TitleEditFormRequest;
use App\Http\Requests\TitleCreateFormRequest;

class TitlesController extends Controller
{
    /**
     * Display a listing of all the titles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Title::class);

        $titles = Title::all();

        return response()->view('titles.index', ['titles' => $titles]);
    }

    /**
     * Show the form for creating a new title.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Title::class);

        return response()->view('titles.create', ['title' => new Title]);
    }

    /**
     * Store a newly created title.
     *
     * @param TitleCreateFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TitleCreateFormRequest $request)
    {
        $this->authorize('create', Title::class);

        Title::create($request->only('name', 'slug', 'introduced_at'));

        return redirect()->route('titles.index');
    }

    /**
     * Display the specified title.
     *
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function show(Title $title)
    {
        $this->authorize('show', Title::class);

        return response()->view('titles.show', ['title' => $title]);
    }

    /**
     * Show the form for editing a title.
     *
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function edit(Title $title)
    {
        $this->authorize('edit', Title::class);

        return response()->view('titles.edit', ['title' => $title]);
    }

    /**
     * Update the specified title.
     *
     * @param  TitleEditFormRequest  $request
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function update(TitleEditFormRequest $request, Title $title)
    {
        $this->authorize('edit', Title::class);

        $title->update($request->only('name', 'slug', 'introduced_at'));

        return redirect()->route('titles.index');
    }

    /**
     * Delete the specified title.
     *
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function destroy(Title $title)
    {
        $this->authorize('delete', Title::class);

        $title->delete();

        return redirect()->route('titles.index');
    }
}

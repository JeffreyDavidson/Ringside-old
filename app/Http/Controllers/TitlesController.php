<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TitlesController extends Controller
{
    /**
     * Display a listing of all the titles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titles = Title::all();

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($titles);
        }

        return response()->view('titles.index', ['titles' => $titles]);
    }

    /**
     * Show the form for creating a new title.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('titles.create', ['title' => new Title]);
    }

    /**
     * Store a newly created title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:titles,name',
            'slug' => 'required|unique:titles,slug',
            'introduced_at' => 'required|date'
        ]);
        
        $title = Title::create([
            'name' => request('name'),
            'slug' => request('slug'),
            'introduced_at' => request('introduced_at')
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($title);
        }

        return redirect(route('titles.index'));
    }

    /**
     * Display the specified title.
     *
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function show(Title $title)
    {
        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($title);
        }

        return view('titles.show', ['title' => $title]);
    }

    /**
     * Show the form for editing a title.
     *
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function edit(Title $title)
    {
        return response()->view('titles.edit', ['title' => $title]);
    }

    /**
     * Update the specified title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Title $title)
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique('titles' ,'name')->ignore($title->id)],
            'slug' => ['required', Rule::unique('titles' ,'slug')->ignore($title->id)],
            'introduced_at' => 'required|date'
        ]);

        $title->update([
            'name' => request('name'),
            'slug' => request('slug'),
            'introduced_at' => request('introduced_at')
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($title);
        }

        return redirect(route('titles.index'));
    }

    /**
     * Delete the specified title.
     *
     * @param  Title $title
     * @return \Illuminate\Http\Response
     */
    public function destroy(Title $title)
    {
        $title->delete();

        return redirect(route('titles.index'));
    }
}

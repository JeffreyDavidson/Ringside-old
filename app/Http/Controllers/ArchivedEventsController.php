<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ArchivedEventsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('archive', Event::class);

        $event = Event::findOrFail($request->input('event_id'));

        $event->archive();

        return redirect()->route('events.index');
    }
}

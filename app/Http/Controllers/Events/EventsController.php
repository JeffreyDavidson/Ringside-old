<?php

namespace App\Http\Controllers\Events;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventEditFormRequest;
use App\Http\Requests\EventCreateFormRequest;

class EventsController extends Controller
{
    /** @var string */
    protected $authorizeResource = Event::class;

    /**
     * Show the form for creating an event.
     *
     * @return \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function create(Event $event)
    {
        return view('events.create', compact('event'));
    }

    /**
     * Store a newly created event.
     *
     * @param  \App\Http\Requests\EventCreateFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EventCreateFormRequest $request)
    {
        Event::create($request->all());

        return redirect()->route('scheduled-events.index');
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     *
     * @param  \App\Http\Requests\EventEditFormRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EventEditFormRequest $request, Event $event)
    {
        $event->update($request->all());

        return redirect()->route('scheduled-events.index');
    }

    /**
     * Delete the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->back();
    }
}

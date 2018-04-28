<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\EventEditFormRequest;
use App\Http\Requests\EventCreateFormRequest;

class EventsController extends Controller
{
    /**
     * Display a listing of all events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Event::class);

        $events = Event::with('venue')->paginate(10);

        return response()->view('events.index', ['events' => $events]);
    }

    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Event::class);

        return response()->view('events.create', ['event' => new Event]);
    }

    /**
     * Store a newly created event.
     *
     * @param  EventCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventCreateFormRequest $request)
    {
        $this->authorize('create', Event::class);

        Event::create($request->only('name', 'slug', 'date', 'venue_id'));

        return redirect()->route('events.index');
    }

    /**
     * Display the specified event.
     *
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $this->authorize('show', Event::class);

        return response()->view('events.show', ['event' => $event]);
    }

    /**
     * Show the form for editing an event.
     *
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $this->authorize('edit', Event::class);

        return response()->view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified event.
     *
     * @param EventEditFormRequest $request
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventEditFormRequest $request, Event $event)
    {
        $this->authorize('edit', Event::class);

        $event->update($request->only('name', 'slug', 'date', 'venue_id'));

        return redirect()->route('events.index');
    }

    /**
     * Delete the specified event.
     *
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', Event::class);

        $event->delete();

        return redirect()->route('events.index');
    }
}

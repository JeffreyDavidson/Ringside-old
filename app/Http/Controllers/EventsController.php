<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\EventEditFormRequest;
use App\Http\Requests\EventCreateFormRequest;

class EventsController extends Controller
{
    protected $authorizeResource = Event::class;

    /**
     * Display a listing of all events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scheduledEvents = Event::scheduled()->with('venue')->paginate(10);
        $previousEvents = Event::past()->with('venue')->paginate(10);
        $archivedEvents = Event::past()->archived()->with('venue')->paginate(10);

        return view('events.index', compact('scheduledEvents', 'previousEvents', 'archivedEvents'));
    }

    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        return view('events.create', compact('event'));
    }

    /**
     * Store a newly created event.
     *
     * @param  \App\Http\Requests\EventCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventCreateFormRequest $request)
    {
        Event::create($request->all());

        return redirect()->route('events.index');
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(EventEditFormRequest $request, Event $event)
    {
        $event->update($request->all());

        return redirect()->route('events.index');
    }

    /**
     * Delete the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index');
    }
}

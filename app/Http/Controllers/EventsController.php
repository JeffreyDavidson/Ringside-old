<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventFormRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventsController extends Controller
{
    /**
     * Display a listing of all events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::with('venue')->get();

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($events);
        }

        return response()->view('events.index', ['events' => $events]);
    }

    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('events.create', ['event' => new Event]);
    }

    /**
     * Store a newly created event.
     *
     * @param  EventFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventFormRequest $request)
    {
        $event = Event::create([
            'name' => request('name'),
            'slug' => request('slug'),
            'date' => request('date'),
            'venue_id' => request('venue_id')
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($event);
        }

        return redirect(route('events.index'));
    }

    /**
     * Display the specified event.
     *
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($event);
        }

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
        return response()->view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique('events' ,'name')->ignore($event->id)],
            'slug' => ['required', Rule::unique('events' ,'slug')->ignore($event->id)],
            'date' => 'required|date_format:"m/d/Y"',
            'time' => 'required|date_format:"H:ia"',
            'venue_id' => 'required|exists:staff,venue_id,deleted_at,NULL'
        ]);

        $event->update([
            'name' => request('name'),
            'address' => request('address'),
            'city' => request('city'),
            'state' => request('state'),
            'postcode' => request('postcode')
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            return response()->json($event);
        }

        return redirect(route('events.index'));
    }

    /**
     * Delete the specified event.
     *
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect(route('events.index'));
    }
}

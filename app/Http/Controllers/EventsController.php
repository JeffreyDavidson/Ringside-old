<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventCreateFormRequest;
use App\Http\Requests\EventEditFormRequest;
use App\Models\Event;
use Illuminate\Http\Request;

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

        $events = Event::with('venue')->get();

        return response()->view('events.index', ['events' => $events]);
    }

    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$this->authorize('create', Event::class);

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

        Event::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'date' => $request->date,
            'venue_id' => $request->venue_id,
        ]);

        //foreach ($request->matches as $match) {
        //    $match->match_nuumber = '';
        //    $match->match_type_id = '';
        //    $match->preview = '';
        //}

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
        //dd($event->matches);
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
     * @param EventEditFormRequest $request
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventEditFormRequest $request, Event $event)
    {
        $event->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
        ]);

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
        $event->delete();

        return redirect()->route('events.index');
    }
}

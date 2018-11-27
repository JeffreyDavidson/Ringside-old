<?php

namespace App\Http\Controllers\Events;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Exceptions\EventHasPastException;
use App\Http\Requests\EventEditFormRequest;
use App\Http\Requests\EventCreateFormRequest;

class EventsController extends Controller
{
    /** @var string */
    protected $authorizeResource = Event::class;

    /**
     * Show the form for creating an event.
     *
     * @param \App\Models\Event  $event
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
        $event = Event::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'date' => $request->date,
            'number_of_matches' => $request->number_of_matches,
            'venue_id' => $request->venue_id
        ]);

        if ($request->has('matches')) {
            $event->matches()->createMany($request->matches);
        }

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
        $this->authorize('update', $event);
        
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     *
     * @param  \App\Http\Requests\EventEditFormRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \App\Exceptions\EventHasPastException
     */
    public function update(EventEditFormRequest $request, Event $event)
    {
        $event->update($request->all());

        if ($event->isArchived()) {
            return redirect()->route('archived-events.index');
        }

        if (!$event->isScheduled()) {
            return redirect()->route('past-events.index');
        }

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

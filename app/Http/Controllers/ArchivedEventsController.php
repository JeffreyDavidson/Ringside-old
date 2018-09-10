<?php

namespace App\Http\Controllers;

use App\Models\Event;

class ArchivedEventsController extends Controller
{
    /** @var string */
    protected $authorizeResource = Event::class;

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return [
            'store' => 'archive',
            'destroy' => 'restore',
        ];
    }

    /**
     * Store a newly created archived event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Event $event)
    {
        $event->archive();

        return redirect()->route('events.index');
    }

    /**
     * Activate an archived event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->activate();

        return redirect()->route('events.index');
    }
}

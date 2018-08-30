<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ArchivedEventsController extends Controller
{
    protected $authorizeResource = Event::class;

    protected function resourceAbilityMap()
    {
        return [
            'store' => 'archive',
            'destroy' => 'restore'
        ];
    }

    /**
     * Store a newly created archived event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event->archive();

        return redirect()->route('events.index');
    }

    public function destroy(Event $event)
    {
        $event->restore();

        return redirect()->route('events.index');
    }
}

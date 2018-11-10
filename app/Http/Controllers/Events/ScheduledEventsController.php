<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;

class ScheduledEventsController extends Controller
{
    /** @var string */
    protected $authorizeResource = Event::class;

    /**
     * Display a listing of all scheduled events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $scheduledEvents = Event::scheduled()->with('venue')->paginate(10);

        return view('events.scheduled', compact('scheduledEvents'));
    }
}

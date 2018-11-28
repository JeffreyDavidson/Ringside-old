<?php

namespace App\Http\Controllers\Events;

use App\Models\Event;
use App\Http\Controllers\Controller;

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

<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;

class PastEventsController extends Controller
{
    /** @var string */
    protected $authorizeResource = Event::class;

    /**
     * Display a listing of all past events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pastEvents = Event::past()->with('venue')->paginate(10);

        return view('events.past', compact('pastEvents'));
    }
}

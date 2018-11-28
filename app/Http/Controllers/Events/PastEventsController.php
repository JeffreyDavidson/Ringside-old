<?php

namespace App\Http\Controllers\Events;

use App\Models\Event;
use App\Http\Controllers\Controller;

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

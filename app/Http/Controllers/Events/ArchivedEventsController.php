<?php

namespace App\Http\Controllers\Events;

use App\Models\Event;
use App\Http\Controllers\Controller;

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
            'index' => 'index',
            'store' => 'archive',
            'destroy' => 'unarchive',
        ];
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
    }

    /**
     * Display a listing of all archived events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $archivedEvents = Event::archived()->with('venue')->paginate(10);

        return view('events.archived', compact('archivedEvents'));
    }

    /**
     * Archives a past event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Event $event)
    {
        $event->archive();

        return redirect()->route('past-events.index');
    }

    /**
     * Unarchives an archived event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->unarchive();

        return redirect()->route('archived-events.index');
    }
}

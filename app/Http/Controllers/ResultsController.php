<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function edit(Event $event)
    {
        $this->authorize('editResults', Event::class);

        return view('events.results', ['event' => $event]);
    }

    public function update(Event $event)
    {

    }
}

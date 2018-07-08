<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Match;
use App\Http\Requests\EventResultsFormRequest;

class ResultsController extends Controller
{
    /**
     * Show the form for editing an events results.
     *
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $this->authorize('editResults', Event::class);

        return view('events.results', ['event' => $event]);
    }

    /**
     * Update the specified event with results from matches.
     *
     * @param EventResultsFormRequest $request
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventResultsFormRequest $request, Event $event)
    {
        foreach ($request->matches as $index => $match) {
            $retrievedMatch = Match::where('match_number', $index + 1)->where('event_id', $event->id)->first();
            $retrievedMatch->update([
                'match_decision_id' => $match['match_decision_id'],
                'winner_id' => $match['winner_id'],
                'result' => $match['result']
            ]);

            $losers = $retrievedMatch->wrestlers->except($match['winner_id']);

            $retrievedMatch->losers()->saveMany($losers);

            if ($retrievedMatch->isTitleMatch()) {
                foreach ($retrievedMatch->titles as $title) {
                    if ($title->hasAChampion()) {
                        if (! $retrievedMatch->winner->hasTitle($title) && $retrievedMatch->decision->titleCanChangeHands()) {
                            $retrievedMatch->winner->winTitle($title, $retrievedMatch->date);
                        } else {
                            $title->champion->increment('successful_defenses');
                        }
                    } elseif ($retrievedMatch->decision->titleCanBeWon()) {
                        $retrievedMatch->winner->winTitle($title, $retrievedMatch->date);
                    }
                }
            }
        }

        return redirect()->route('events.index');
    }
}

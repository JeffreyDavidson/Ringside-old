<?php

namespace App\Services;

use App\Models\Match;

class UpdateMatchResults
{
    protected $matches;
    protected $event;

    public function __construct($matches, $event)
    {
        $this->matches = $matches;
        $this->event = $event;
    }

    public function save()
    {
        foreach ($this->matches as $index => $match) {
            $retrievedMatch = Match::withMatchNumber($index + 1)->forEvent($this->event)->first();

            $retrievedMatch->update([
                'match_decision_id' => $match['match_decision_id'],
                'result' => $match['result'],
            ]);
            // dd('fasfkljdskf;adfjksd');
            $winners = $match['winners'];
            $losers = array_values(array_diff($retrievedMatch->wrestlers->modelKeys(), $winners));

            $retrievedMatch->setWinners($winners);
            $retrievedMatch->setLosers($losers);

            if ($retrievedMatch->isTitleMatch()) {
                foreach ($retrievedMatch->titles as $title) {
                    // dd($title->isVacant());
                    if (!$title->isVacant()) {
                        // dd('title is not vacant');
                        // dd($retrievedMatch->decision->titleCanChangeHands());
                        // dd($retrievedMatch->winners->contains->hasTitle($title));
                        if (!$retrievedMatch->winners->contains->hasTitle($title) && $retrievedMatch->decision->titleCanChangeHands()) {
                            dd('lalala');
                            $title->currentChampion->loseTitle($retrievedMatch->date);
                            $retrievedMatch->winners->winTitle($title, $retrievedMatch->date);
                        } else {
                            // dd('increase defenses');
                            // dd($title->currentChampion->pivot->successful_defenses);
                            // dd($title->currentChampion->pivot->toArray());
                            // dd($title->currentChampion->pivot->increment('successful_defenses'));
                            $title->currentChampion->pivot->increment('successful_defenses');
                            dd($title->currentChampion->fresh()->pivot->successful_defenses);
                            dd($title->currentChampion->pivot->successful_defenses);
                        }
                    } elseif ($retrievedMatch->decision->titleCanBeWon()) {
                        dd('no champion');
                        $retrievedMatch->winners->each->winTitle($title, $retrievedMatch->date);
                    }
                }
            }
        }
    }
}

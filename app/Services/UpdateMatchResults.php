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
                'winner_id' => $match['winner_id'],
                'result' => $match['result'],
            ]);

            $losers = $retrievedMatch->wrestlers->except($match['winner_id']);

            $retrievedMatch->losers()->saveMany($losers);

            if ($retrievedMatch->isTitleMatch()) {
                foreach ($retrievedMatch->titles as $title) {
                    if (!$title->isVacant()) {
                        if (!$retrievedMatch->winner->hasTitle($title) && $retrievedMatch->decision->titleCanChangeHands()) {
                            $title->currentChampion->loseTitle($retrievedMatch->date);
                            $retrievedMatch->winner->winTitle($title, $retrievedMatch->date);
                        } else {
                            $title->currentChampion->increment('successful_defenses');
                        }
                    } elseif ($retrievedMatch->decision->titleCanBeWon()) {
                        $retrievedMatch->winner->winTitle($title, $retrievedMatch->date);
                    }
                }
            }
        }
    }
}

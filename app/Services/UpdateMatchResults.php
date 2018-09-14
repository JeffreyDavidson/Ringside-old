<?php

namespace App\Services;

use App\Models\Match;

class UpdateMatchResults
{
    /** @var array $matches */
    protected $matches;

    /** @var \App\Models\Event $event */
    protected $event;

    /**
     * Create a new UpdateMatchResults instance.
     *
     * @param array $matches
     * @param \App\Models\Event $event
     * @return void
     */
    public function __construct($matches, $event)
    {
        $this->matches = $matches;
        $this->event = $event;
    }

    /**
     * Update each match of an event with its result.
     *
     * @return void
     */
    public function save()
    {
        foreach ($this->matches as $index => $match) {
            $retrievedMatch = Match::withMatchNumber($index + 1)->forEvent($this->event)->first();

            $retrievedMatch->update([
                'match_decision_id' => $match['match_decision_id'],
                'result' => $match['result'],
            ]);

            $winners = $match['winners'];
            $losers = array_values(array_diff($retrievedMatch->wrestlers->modelKeys(), $winners));

            $retrievedMatch->setWinners($winners);
            $retrievedMatch->setLosers($losers);

            if ($retrievedMatch->isTitleMatch()) {
                foreach ($retrievedMatch->titles as $title) {
                    if (! $title->isVacant()) {
                        if (! $retrievedMatch->winners->contains->hasTitle($title) && $retrievedMatch->decision->titleCanChangeHands()) {
                            $title->currentChampion->loseTitle($title, $retrievedMatch->date);
                            $retrievedMatch->winners->each->winTitle($title, $retrievedMatch->date);
                        } else {
                            $title->currentChampion()->updateExistingPivot($title->currentChampion->id, [
                                'successful_defenses' => $title->currentChampion->pivot->successful_defenses + 1,
                            ]);
                        }
                    } elseif ($retrievedMatch->decision->titleCanBeWon()) {
                        $retrievedMatch->winners->each->winTitle($title, $retrievedMatch->date);
                    }
                }
            }
        }
    }
}

<?php

namespace App\Services;

class AddMatchesToEvent
{
    protected $matches;
    protected $event;

    public function __construct($matches, $event)
    {
        $this->matches = $matches;
        $this->event = $event;
    }

    public function schedule()
    {
        foreach ($this->matches as $match) {
            $matchObj = $this->event->matches()->create([
                'match_type_id' => $match['match_type_id'],
                'stipulation_id' => $match['stipulation_id'],
                'preview' => $match['preview'],
            ]);

            if (!empty($match['titles'])) {
                foreach ($match['titles'] as $titleId) {
                    $matchObj->titles()->attach($titleId);
                }
            }

            if (!empty($match['referees'])) {
                foreach ($match['referees'] as $refereeId) {
                    $matchObj->referees()->attach($refereeId);
                }
            }

            if (!empty($match['wrestlers'])) {
                foreach ($match['wrestlers'] as $groupingId => $wrestlersArray) {
                    foreach ($wrestlersArray as $wrestlerId) {
                        $matchObj->wrestlers()->attach($wrestlerId, ['side_number' => $groupingId]);
                    }
                }
            }
        }
    }
}

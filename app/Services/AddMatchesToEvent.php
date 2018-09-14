<?php

namespace App\Services;

class AddMatchesToEvent
{
    /** @var array $matches */
    protected $matches;

    /** @var \App\Models\Event $event */
    protected $event;

    /**
     * Create a new AddMatchesToEvent instance.
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
     * Schedule all matches for an event.
     *
     * @return void
     */
    public function schedule()
    {
        foreach ($this->matches as $match) {
            $matchObj = $this->event->matches()->create([
                'match_type_id' => $match['match_type_id'],
                'stipulation_id' => $match['stipulation_id'],
                'preview' => $match['preview'],
            ]);

            if (! empty($match['titles'])) {
                $matchObj->addTitles($match['titles']);
            }

            $matchObj->addReferees($match['referees']);
            $matchObj->addWrestlers($match['wrestlers']);
        }
    }
}

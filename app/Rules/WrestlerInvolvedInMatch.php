<?php

namespace App\Rules;

use App\Models\Event;
use App\Models\Match;
use Illuminate\Contracts\Validation\Rule;

class WrestlerInvolvedInMatch implements Rule
{
    /**
     * @var \App\Models\Event
     */
    private $event;

    /**
     * @var int
     */
    private $matchNumber;

    /**
     * Create a new rule instance.
     *
     * @param  \App\Models\Event  $event
     * @param  int  $matchNumber
     * @return void
     */
    public function __construct(Event $event, $matchNumber)
    {
        $this->event = $event;
        $this->matchNumber = $matchNumber;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Match::forEvent($this->event)
            ->withMatchNumber($this->matchNumber)
            ->withWrestler($value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The winner was not involved in the match.';
    }
}

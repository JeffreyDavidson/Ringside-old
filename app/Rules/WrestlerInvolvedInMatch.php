<?php

namespace App\Rules;

use App\Models\Match;
use App\Models\Event;
use Illuminate\Contracts\Validation\Rule;

class WrestlerInvolvedInMatch implements Rule
{
    /** @var */
    private $matchNumber;

    /** @var */
    private $event;

    /**
     * Create a new rule instance.
     *
     * @param  int  $matchNumber
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function __construct($matchNumber, Event $event)
    {
        $this->matchNumber = $matchNumber;
        $this->event = $event;
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
        return Match::withMatchNumber($this->matchNumber)
            ->forEvent($this->event)
            ->whereHas('wrestlers', function ($query) use ($value) {
                $query->where('wrestlers.id', $value);
            })
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The winner of this match '.$this->matchNumber.' not involved in the match.';
    }
}

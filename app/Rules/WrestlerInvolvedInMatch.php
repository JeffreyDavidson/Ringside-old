<?php

namespace App\Rules;

use App\Models\Match;
use Illuminate\Contracts\Validation\Rule;

class WrestlerInvolvedInMatch implements Rule
{
    private $matchNumber;

    private $event;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($matchNumber, $event)
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
        return ($match = Match::query()->withMatchNumber($this->matchNumber)->forEvent($this->event)->first()) && $match->wrestlers->contains('id', $value);
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

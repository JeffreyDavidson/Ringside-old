<?php

namespace App\Rules;

use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class QualifiedForMatch implements Rule
{
    /**
     * @var \App\Models\Wrestler
     */
    private $eventDate;

    /**
     * Create a new rule instance.
     *
     * @param $eventDate
     */
    public function __construct($eventDate)
    {
        $this->eventDate = $eventDate;
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
        $wrestler = Wrestler::find($value);
        return $wrestler->hired_at->lte(Carbon::parse($this->eventDate));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This wrestler is not qualified for the match.';
    }
}

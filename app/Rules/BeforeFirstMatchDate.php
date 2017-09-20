<?php

namespace App\Rules;

use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class BeforeFirstMatchDate implements Rule
{
    /**
     * @var \App\Models\Wrestler
     */
    private $wrestler;

    /**
     * Create a new rule instance.
     *
     * @param \App\Models\Wrestler $wrestler
     */
    public function __construct(Wrestler $wrestler)
    {
        $this->wrestler = $wrestler;
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
        //dd($this->wrestler->hasMatches());
        if ($this->wrestler->hasMatches()) {
            return Carbon::parse($value)
                ->lte($this->wrestler->firstMatchDate());
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The hired at date cannot be AFTER the wrestler\'s first match.';
    }
}

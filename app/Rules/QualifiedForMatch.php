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
        return $this->wrestler->hired_at->lte(Carbon::parse($value));
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

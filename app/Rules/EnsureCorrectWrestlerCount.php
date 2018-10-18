<?php

namespace App\Rules;

use App\Models\MatchType;
use Illuminate\Contracts\Validation\Rule;

class EnsureCorrectWrestlerCount implements Rule
{
    private $match_competitors;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($match_type_id)
    {
        if (is_null($match_type_id)) {
            return false;
        }

        $this->match_competitors = MatchType::whereKey($match_type_id)->value('total_competitors');
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
        return count($value) !== $this->match_competitors;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This match requires ' . $this->match_competitors . ' competitors.';
    }
}

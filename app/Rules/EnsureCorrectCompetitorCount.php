<?php

namespace App\Rules;

use App\Models\MatchType;
use Illuminate\Contracts\Validation\Rule;

class EnsureCorrectCompetitorCount implements Rule
{
    /**
     * @var int
     */
    private $matchCompetitors;

    /**
     * Create a new Rule instance.
     *
     * @param  int  $matchTypeId
     * @return void
     */
    public function __construct($matchTypeId)
    {
        $this->matchCompetitors = MatchType::whereKey($matchTypeId)->value('total_competitors');
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
        return count($value) == $this->matchCompetitors;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This match requires {$this->matchCompetitors} competitors.";
    }
}

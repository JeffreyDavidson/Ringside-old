<?php

namespace App\Rules;

use App\Models\MatchType;
use Illuminate\Contracts\Validation\Rule;

class EnsureCorrectCompetitorCount implements Rule
{
    /**
     * @var int
     */
    private $matchTypeCompetitorsCount;

    /**
     * @var int
     */
    private $matchCompetitorsCount;

    /**
     * Create a new Rule instance.
     *
     * @param  int  $matchTypeId
     * @return void
     */
    public function __construct($matchTypeId, $matchCompetitorsCount)
    {
        $this->matchTypeCompetitorsCount = MatchType::whereKey($matchTypeId)->value('total_competitors');
        $this->matchCompetitorsCount = $matchCompetitorsCount;
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
        return $matchTypeCompetitorsCount == $this->matchCompetitorsCount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This match requires '.$this->matchTypeCompetitors.' competitors.';
    }
}

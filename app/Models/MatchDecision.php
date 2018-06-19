<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchDecision extends Model
{
    /**
     * List of slugs that a title can change hands.
     *
     * @var array
     */
    protected $decisionsTitlesChangeHands = [
        'pinfall',
        'submission',
        'stipulation'
    ];

    /**
     * Checks to see if the match decision can cause a title to change hands.
     *
     * @return bool
     */
    public function titleCanChangeHands()
    {
        return in_array($this->slug, $this->decisionsTitlesChangeHands);
    }
}

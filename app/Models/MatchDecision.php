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
        'knockout',
        'stipulation',
    ];

    /**
     * List of slugs that a title can be won with no previous champion crowned.
     *
     * @var array
     */
    protected $decisionsATitleCanBeWon = [
        'pinfall',
        'submission',
        'dq',
        'countout',
        'knockout',
        'stipulation',
        'forfeit',
        'revdecision',
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

    /**
     * Checks to see if the match decision can cause a title to be given to a non champion.
     *
     * @return bool
     */
    public function titleCanBeWon()
    {
        return in_array($this->slug, $this->decisionsATitleCanBeWon);
    }

    /**
     * Checks to see if the match decision can cause a title to be given to a non champion.
     *
     * @return bool
     */
    public function titleCanNotBeWon()
    {
        return ! in_array($this->slug, $this->decisionsATitleCanBeWon);
    }

    public function scopeTitleCanBeWonBySlug($query)
    {
        return $query->whereIn('slug', $this->decisionsATitleCanBeWon);
    }

    public function scopeTitleCanNotBeWonBySlug($query)
    {
        return $query->whereNotIn('slug', $this->decisionsATitleCanBeWon);
    }
}

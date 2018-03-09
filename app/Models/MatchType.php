<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchType extends Model
{
    /**
     * List of match types that need multiple referees.
     *
     * @var array
     */
    protected $needsMultipleReferees = [
        'battleroyal',
        'royalrumble',
    ];

    /**
     * Checks to see if the match type needs additional referees.
     *
     * @return bool
     */
    public function needsMultipleReferees()
    {
        return in_array($this->slug, $this->needsMultipleReferees);
    }
}

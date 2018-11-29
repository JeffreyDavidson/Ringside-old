<?php

namespace App\Models;

use App\Models\RosterMember;

class Wrestler extends RosterMember
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'hometown', 'height', 'weight', 'signature_move', 'is_active', 'hired_at'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\WrestlerPresenter';
}

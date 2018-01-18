<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Injury extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['injured_at', 'healed_at'];

    /**
     * Heals the wrestler from being injured.
     *
     * @return bool
     */
    public function healed($healedAt)
    {
        return $this->update(['healed_at' => $healedAt ?: $this->freshTimestamp()]);
    }
}

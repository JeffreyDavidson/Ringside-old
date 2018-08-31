<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEvents;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasEvents, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'address', 'city', 'state', 'postcode'];
}

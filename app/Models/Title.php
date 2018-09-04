<?php

namespace App\Models;

use App\Traits\HasStatus;
use App\Traits\HasMatches;
use App\Traits\HasRetirements;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use HasMatches, HasRetirements, HasStatus, Presentable, SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'introduced_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'is_active', 'introduced_at'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\TitlePresenter';

    /**
     * A title can have many champions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champions()
    {
        return $this->hasMany(Championship::class);
    }

    /**
     * Returns the current champion for the title.
     *
     * @return \App\Models\Champion
     */
    public function currentChampion()
    {
        return $this->champions()->current()->toHasOne();
    }

    /**
     * Checks to see if the title is currently vacant.
     *
     * @return bool
     */
    public function isVacant()
    {
        return $this->champions()->current()->doesntExist();
    }
}

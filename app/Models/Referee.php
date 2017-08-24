<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    use Presentable, SoftDeletes;

    protected $presenter = 'App\Presenters\RefereePresenter';
}

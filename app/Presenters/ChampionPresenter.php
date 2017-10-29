<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class ChampionPresenter extends Presenter {

    public function wonOn()
    {
        return $this->model->won_on->format('F j, Y');
    }

    public function lostOn()
    {
        return $this->model->lost_on ? $this->model->lost_on->format('F j, Y') : 'Present';
    }

    public function lengthOfReign()
    {
        return $this->model->won_on->diffInDays($this->model->lost_on) .' days';
    }
}
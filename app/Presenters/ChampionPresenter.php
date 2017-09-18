<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class ChampionPresenter extends Presenter {

    public function formattedWonOn()
    {
        return $this->model->won_on->format('F j, Y');
    }

    public function formattedLostOn()
    {
        return $this->model->lost_on ? $this->model->lost_on->format('F j, Y') : 'Present';
    }

    public function lengthOfReign()
    {
        return $this->model->won_on->diffForHumans($this->model->lost_on, true);
    }
}
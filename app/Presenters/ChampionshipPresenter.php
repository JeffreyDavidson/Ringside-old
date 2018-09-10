<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class ChampionshipPresenter extends Presenter
{
    /**
     * Formats won on date.
     *
     * @return string
     */
    public function wonOn()
    {
        return $this->model->won_on->format('F j, Y');
    }

    /**
     * Formats lost on date.
     *
     * @return string
     */
    public function lostOn()
    {
        return $this->model->lost_on ? $this->model->lost_on->format('F j, Y') : 'Present';
    }

    /**
     * Calculates how long a championship has been held.
     *
     * @return string
     */
    public function lengthOfReign()
    {
        return $this->model->lost_on ? $this->model->won_on->diffInDays($this->model->lost_on).' days' : 'Present';
    }
}

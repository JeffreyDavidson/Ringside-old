<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class EventPresenter extends Presenter
{
    /**
     * Formats event date.
     *
     * @return string
     */
    public function date()
    {
        return $this->model->date->format('F jS, Y');
    }

    /**
     * Formats event form date.
     *
     * @return string
     */
    public function formattedFormDate()
    {
        return $this->model->date->format('m/d/Y');
    }

    /**
     * Formats time of event.
     *
     * @return string
     */
    public function time()
    {
        return $this->model->date->format('h:ia');
    }
}

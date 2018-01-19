<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class EventPresenter extends Presenter
{
    public function date()
    {
        return $this->model->date->format('F jS, Y');
    }

    public function formattedFormDate()
    {
        return $this->model->date->format('m/d/Y');
    }

    public function time()
    {
        return $this->model->date->format('h:ia');
    }
}

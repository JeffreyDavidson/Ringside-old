<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class RefereePresenter extends Presenter
{
    /**
     * Get the referee's full name.
     *
     * @return string
     */
    public function full_name()
    {
        return $this->model->first_name.' '.$this->model->last_name;
    }
}
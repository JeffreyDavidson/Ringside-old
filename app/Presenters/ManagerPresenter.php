<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class ManagerPresenter extends Presenter
{
    /**
     * Get the referee's full name.
     *
     * @return string
     */
    public function fullName()
    {
        return $this->model->first_name.' '.$this->model->last_name;
    }
}
<?php

namespace App\Presenters\Roster;

use Laracodes\Presenter\Presenter;

class ManagerPresenter extends Presenter
{
    /**
     * Formats full name of manager.
     *
     * @return string
     */
    public function fullName()
    {
        return $this->model->first_name.' '.$this->model->last_name;
    }
}

<?php

namespace App\Presenters\Roster;

use Laracodes\Presenter\Presenter;

class RefereePresenter extends Presenter
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

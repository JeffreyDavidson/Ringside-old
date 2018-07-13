<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class RetirementPresenter extends Presenter
{
    public function retiredAt()
    {
        return $this->model->retired_at->format('F j, Y');
    }
}

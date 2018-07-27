<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class RetirementPresenter extends Presenter
{
    /**
     * Formats retired at date for model.
     *
     * @return string
     */
    public function retiredAt()
    {
        return $this->model->retired_at->format('F j, Y');
    }
}

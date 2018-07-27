<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class TitlePresenter extends Presenter
{
    /**
     * Formats introduced at date for model.
     *
     * @return string
     */
    public function introducedAt()
    {
        return $this->model->introduced_at->format('F j, Y');
    }
}

<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class TitlePresenter extends Presenter
{
    public function introducedAt()
    {
        return $this->model->introduced_at->format('F j, Y');
    }
}

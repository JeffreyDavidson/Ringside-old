<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class TitlePresenter extends Presenter {

    public function introduced_at()
    {
        return $this->model->introduced_at->format('F j, Y');
    }

    public function retired_at()
    {
        return $this->model->retired_at->format('F j, Y');
    }
}
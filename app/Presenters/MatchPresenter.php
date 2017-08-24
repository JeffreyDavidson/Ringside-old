<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class MatchPresenter extends Presenter {

    public function wrestlers()
    {
        return $this->model->wrestlers->implode('name', ' vs. ');
    }

    public function referees()
    {
        return $this->model->referees->map(function ($item) {
            return $item->present()->full_name();
        })->implode(', ');
    }

    public function stipulations()
    {
        return $this->model->stipulations->implode('name', ', ');
    }

    public function match_number($loop)
    {
        if ($loop->first) {
            return 'Opening Match';
        } elseif ($loop->last) {
            return 'Main Event';
        } else {
            return 'Match #'.$this->model->match_number;
        }
    }
}

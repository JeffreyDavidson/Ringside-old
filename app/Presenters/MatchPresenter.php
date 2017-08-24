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
        //dd($this->model->referees);
        return $this->model->referees->map(function ($item, $key) {
            dd($item);
            //dd($item->full_name);
            //dd($item->full_name);
            //dd($item->present()->model->full_name);
            //dd($item->present()->full_name);
            //dd($item->model->present()->full_name);
            return $item->present()->full_name;
        });
    }
}

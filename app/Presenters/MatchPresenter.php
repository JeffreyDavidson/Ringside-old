<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class MatchPresenter extends Presenter
{
    /** TODO: Check to see if groupBy should be moved to another section of application. */
    public function wrestlers()
    {
        $groupedWrestlers = $this->model->wrestlers->groupBy(function ($item, $key) {
            return $item->pivot->side_number;
        });

        // Find out what I should do for the grouped wrestlers
        // return $this->model->wrestlers->groupBySide->map(function ($group) {
        //     return $group->pluck('name')->implode(' & ');
        // })->implode(' vs. ');

        return $groupedWrestlers->map(function ($group) {
            return $group->pluck('name')->implode(' & ');
        })->implode(' vs. ');
    }

    public function referees()
    {
        return $this->model->referees->map(function ($item) {
            return $item->present()->fullName();
        })->implode(' & ');
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

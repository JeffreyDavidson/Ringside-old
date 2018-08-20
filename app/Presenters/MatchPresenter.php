<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class MatchPresenter extends Presenter
{
    /**
     * Formats collection of wrestlers for match.
     *
     * @return string
     */
    public function wrestlers()
    {
        if (in_array($this->model->type->slug, ['royalrumble', 'battleroyal'])) {
            return $this->model->type->name;
        }

        $groupedWrestlers = $this->model->wrestlers->groupBy(function ($item, $key) {
            return $item->pivot->side_number;
        });

        return $groupedWrestlers->map(function ($group) {
            return $group->pluck('name')->implode(' & ');
        })->implode(' vs. ');
    }

    /**
     * Formats collection of referees for match.
     *
     * @return string
     */
    public function referees()
    {
        return $this->model->referees->map(function ($item) {
            return $item->present()->fullName();
        })->implode(' & ');
    }

    /**
     * Formats match number for match.
     *
     * @return string
     */
    public function match_number()
    {
        $numberOfMatches = $this->model->event->matches()->count(); // use ->matches->count() if relation already loaded to avoid an extra query

        if ($this->model->match_number == $numberOfMatches) {
            return 'Main Event';
        }

        if ($this->model->match_number == 1) {
            return 'Opening Match';
        }

        return 'Match #'.$this->model->match_number;
    }
}

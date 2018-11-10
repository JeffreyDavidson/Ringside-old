<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class WrestlerPresenter extends Presenter
{
    /**
     * Formats the wrestler's height.
     *
     * @return string
     */
    public function height()
    {
        $feet = floor($this->model->height / 12);
        $inches = ($this->model->height % 12);

        return $feet . '\'' . $inches . '"';
    }

    /**
     * Formats wrestler's height in feet.
     *
     * @return int
     */
    public function height_in_feet()
    {
        $feet = floor($this->model->height / 12);

        return (int) $feet;
    }

    /**
     * Formats wrestler's height in inches.
     *
     * @return int
     */
    public function height_in_inches()
    {
        $feet = floor($this->model->height / 12);
        $inches = ($this->model->height % 12);

        return $inches;
    }
}

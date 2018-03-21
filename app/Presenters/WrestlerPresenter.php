<?php

namespace App\Presenters;

use Laracodes\Presenter\Presenter;

class WrestlerPresenter extends Presenter
{
    /**
     * Formats and presenters the height attribute.
     *
     * @return mixed string
     */
    public function height()
    {
        $feet = floor($this->model->height / 12);
        $inches = ($this->model->height % 12);

        return $feet.'\''.$inches.'"';
    }

    /**
     * Formats and presenters the height attribute.
     *
     * @return mixed string
     */
    public function height_in_feet()
    {
        $feet = floor($this->model->height / 12);

        return (int) $feet;
    }

    /**
     * Formats and presenters the height attribute.
     *
     * @return mixed string
     */
    public function height_in_inches()
    {
        $feet = floor($this->model->height / 12);
        $inches = ($this->model->height % 12);

        return (int) $inches;
    }
}

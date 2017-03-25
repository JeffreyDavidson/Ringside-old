<?php

namespace App\Collections;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Collection;

class TitleHistories extends Collection
{
    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new CustomCollection($models);
    }

    public function groupByTitle()
    {
        return $this->groupBy('title_id');
    }
}
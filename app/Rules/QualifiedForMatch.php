<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class QualifiedForMatch implements Rule
{
    /**
     * @var \App\Models\Event
     */
    private $eventDate;

    /**
     * @var \App\Models\*
     */
    private $model;

    /**
     * Create a new rule instance.
     *
     * @param $eventDate
     */
    public function __construct($eventDate, $model)
    {
        $this->eventDate = $eventDate;
        $this->model = $model;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $model)
    {
        return $model->$attribute->lte(Carbon::parse($this->eventDate));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This '.strtolower(class_basename(get_class($this->model))).' is not qualified for the match.';
    }
}

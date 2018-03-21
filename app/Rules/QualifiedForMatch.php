<?php

namespace App\Rules;

use Carbon\Carbon;
use App\Models\Wrestler;
use Illuminate\Contracts\Validation\Rule;

class QualifiedForMatch implements Rule
{
    /**
     * @var \App\Models\Wrestler
     */
    private $eventDate;

    /**
     * Create a new rule instance.
     *
     * @param $eventDate
     */
    public function __construct($eventDate)
    {
        $this->eventDate = $eventDate;
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
        return 'This '.strtolower(get_class($model)).' is not qualified for the match.';
    }
}

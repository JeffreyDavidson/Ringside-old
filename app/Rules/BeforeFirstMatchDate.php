<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class BeforeFirstMatchDate implements Rule
{
    /**
     * @var object
     */
    private $model;

    /**
     * Create a new rule instance.
     *
     * @param  object  $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  string $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->model->hasPastMatches()) {
            return Carbon::parse($value)->lte($this->model->firstMatchDate());
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot be after ' . $this->model->firstMatchDate()->toDateString();
    }
}

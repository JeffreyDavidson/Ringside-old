<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class QualifiedForMatch implements Rule
{
    private $eventDate;
    private $modelClass;
    private $field;

    /**
     * Create a new rule instance.
     *
     * @param $eventDate
     */
    public function __construct($eventDate, $modelClass, $field)
    {
        $this->eventDate = $eventDate;
        $this->modelClass = $modelClass;
        $this->field = $field;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $modelId)
    {
        return $this->modelClass::query()
                    ->whereKey($modelId)
                    ->where($this->field, '<=', Carbon::parse($this->eventDate))
                    ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This '.strtolower(class_basename($this->modelClass)).' is not qualified for the match.';
    }
}

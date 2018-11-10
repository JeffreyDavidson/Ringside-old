<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class QualifiedForMatch implements Rule
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $eventDate;

    /**
     * Create a new rule instance.
     *
     * @param  string  $modelClass
     * @param  string  $field
     * @param  string  $eventDate
     */
    public function __construct($modelClass, $field = 'hired_at', $eventDate = 'now')
    {
        $this->modelClass = $modelClass;
        $this->field = $field;
        $this->eventDate = Carbon::parse($eventDate);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->modelClass::query()
                    ->whereKey($value)
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
        return 'This ' . strtolower(class_basename($this->modelClass)) . ' is not qualified for the match.';
    }
}

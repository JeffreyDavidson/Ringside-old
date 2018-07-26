<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Validation\Rule;
use App\Rules\WrestlerInvolvedInMatch;
use Illuminate\Foundation\Http\FormRequest;

class EventResultsFormRequest extends FormRequest
{
    private $expectedMatchesCount = 0;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('update-event-results');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->expectedMatchesCount = Event::find(request()->event->id)->matches()->count();

        $rules = [
            'matches'                  => ['array', 'size:'.$this->expectedMatchesCount],
            'matches.*.match_decision_id' => ['required', 'integer', 'min:1', Rule::exists('match_decisions', 'id')],
            'matches.*.winner_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('wrestlers', 'id'),
            ],
            'matches.*.result' => ['required', 'string'],
        ];

        if (is_array($this->matches)) {
            foreach ($this->matches as $index => $match) {
                $rules['matches.'.$index.'.winner_id'] = [
                    new WrestlerInvolvedInMatch($index + 1, request()->event),
                ];
            }
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'matches.size' => 'This event should have exactly '.$this->expectedMatchesCount.' '.str_plural('match', $this->expectedMatchesCount).' in size.',
        ];
    }
}

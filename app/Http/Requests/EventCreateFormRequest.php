<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\QualifiedForMatch;
use App\Rules\EnsureCorrectCompetitorCount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class EventCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('create-event');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->all());
        return [
            'name' => ['required', 'string', 'unique:events,name'],
            'slug' => ['required', 'string', 'unique:events,slug'],
            'date' => ['required', 'string', 'date'],
            'venue_id' => ['required', 'integer', 'not_in:0', 'exists:venues,id'],
            'schedule_matches' => ['required', 'boolean'],
            'number_of_matches' => ['required_if:schedule_matches, 1', 'integer', 'min:1'],
            'matches' => ['sometimes', 'array'],
            'matches.*' => ['size:1'],
            'matches.*.match_type_id' => ['required_if:matches.*, 1', 'integer', Rule::exists('match_types', 'id')],
            'matches.*.stipulation_id' => ['nullable', 'integer', Rule::exists('stipulations', 'id')],
            'matches.*.titles' => ['array'],
            'matches.*.titles.*' => ['sometimes', 'distinct', 'integer', Rule::exists('titles', 'id')],
            'matches.*.referees' => ['required_if:matches.*, 1', 'array'],
            'matches.*.referees.*' => ['distinct', 'integer', Rule::exists('referees', 'id')],
            'matches.*.preview' => ['required_if:matches.*, 1', 'string'],
            'matches.*.wrestlers' => ['required_if:matches.*, 1', 'array', new EnsureCorrectCompetitorCount(request()->match_type_id)],
            'matches.*.wrestlers.*.*' => [
                'integer',
                'exists:wrestlers,id',
                'distinct',
                new QualifiedForMatch(Wrestler::class),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'venue_id.required' => 'The venue field is required.',
            'venue_id.not_in' => 'The selected venue is invalid.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->schedule_matches && !$this->matches) {
                $validator->errors()->add('matches', 'Something is wrong with this field!');
            }
        });

        // dd($validator->errors());

    }
}

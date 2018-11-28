<?php

namespace App\Http\Requests;

use App\Models\Title;
use App\Models\Wrestler;
use Illuminate\Validation\Rule;
use App\Rules\QualifiedForMatch;
use App\Rules\EnsureCorrectCompetitorCount;
use Illuminate\Foundation\Http\FormRequest;

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
            'number_of_matches' => ['required', 'integer', 'min:1'],
            'schedule_matches' => ['required', 'boolean'],
            'matches' => ['required_if:schedule_matches, 1', 'sometimes', 'array'],
            'matches.*' => ['min:1'],
            'matches.*.match_type_id' => ['required_if:matches.*, 1', 'integer', Rule::exists('match_types', 'id')],
            'matches.*.stipulation_id' => ['nullable', 'integer', Rule::exists('stipulations', 'id')],
            'matches.*.titles' => ['array'],
            'matches.*.titles.*' => ['sometimes', 'distinct', 'integer', Rule::exists('titles', 'id'), new QualifiedForMatch(Title::class, 'introduced_at')],
            'matches.*.referees' => ['required_if:matches.*, 1', 'array'],
            'matches.*.referees.*' => ['distinct', 'integer', Rule::exists('referees', 'id')],
            'matches.*.preview' => ['required_if:matches.*, 1', 'string'],
            'matches.*.wrestlers' => ['required_if:matches.*, 1', 'array', 'min:2'],
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
            if ($this->schedule_matches && ! $this->matches) {
                $validator->errors()->add('matches', 'You have selected to schedule matches for the even but none were provided!');
            }

            if ($this->matches && is_array($this->matches && ! empty($this->matches))) {
                foreach ($this->matches as $match) {
                    new EnsureCorrectCompetitorCount($match['match_type_id'], count(array_flatten($match['wrestlers'])));
                }
            }
        });
    }
}

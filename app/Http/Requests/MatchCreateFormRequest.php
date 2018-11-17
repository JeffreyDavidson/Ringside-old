<?php

namespace App\Http\Requests;

use App\Models\Wrestler;
use Illuminate\Validation\Rule;
use App\Rules\QualifiedForMatch;
use App\Rules\EnsureCorrectCompetitorCount;
use Illuminate\Foundation\Http\FormRequest;

class MatchCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('create-match');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /**
         * TODO: Need to add rule for a title has to be introduced before the match event date.
         */
        return [
            'match_type_id' => ['bail', 'required', 'integer', Rule::exists('match_types', 'id')],
            'stipulation_id' => ['nullable', 'integer', Rule::exists('stipulations', 'id')],
            'titles' => ['array'],
            'titles.*' => ['sometimes', 'distinct', 'integer', Rule::exists('titles', 'id')],
            'referees' => ['required', 'array'],
            'referees.*' => ['distinct', 'integer', Rule::exists('referees', 'id')],
            'preview' => ['required', 'string'],
            'wrestlers' => ['required', 'array'],
            'wrestlers.*.*' => [
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
            'match_type_id.required' => 'The match type is required.',
            'match_type_id.not_in' => 'The selected match type is invalid.',
            'stipulation_id.required' => 'The stipulation is required.',
            'stipulation_id.integer' => 'The stipulation must be an integer.',
            'titles.not_in' => 'The selected title is invalid.',
            'referees.required' => 'The referees field is required.',
            'referees.not_in' => 'The selected referee is invalid.',
            'preview.required' => 'The preview field is required.',
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
            if (!is_null($this->match_type_id) && is_array($this->wrestlers) && !empty($this->wrestlers)) {
                new EnsureCorrectCompetitorCount($this->match_type_id, count(array_flatten($this->wrestlers)));
            }
        });
    }
}

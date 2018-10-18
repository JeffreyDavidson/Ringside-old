<?php

namespace App\Http\Requests;

use App\Models\Wrestler;
use App\Rules\EnsureCorrectWrestlerCount;
use App\Rules\QualifiedForMatch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        dd(request()->referees);
        $date = request()->event->date;

        /**
         * TODO: Need to add rule for a title has to be introduced before the match event date.
         */
        return [
            'match_type_id' => ['required', 'integer', Rule::exists('match_types', 'id')],
            'stipulation_id' => ['sometimes', 'integer', Rule::exists('stipulations', 'id')],
            'titles' => ['array'],
            'titles.*' => ['sometimes', 'distinct', 'integer', Rule::exists('titles', 'id')],
            'referees' => ['required', 'array'],
            'referees.*' => ['distinct', 'integer', Rule::exists('referees', 'id')],
            'preview' => ['required', 'string'],
            'wrestlers' => ['array', 'required', new EnsureCorrectWrestlerCount(request()->match_type_id)],
            'wrestlers.*.*' => [
                'integer',
                'exists:wrestlers,id',
                'distinct',
                new QualifiedForMatch($date, Wrestler::class, 'hired_at'),
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // dd($validator->errors());
        });
    }
}

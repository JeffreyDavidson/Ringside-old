<?php

namespace App\Http\Requests;

use App\Rules\QualifiedForMatch;
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
        return $this->user()->hasPermission('store-match');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'matches.*' => [
                'required',
                'integer',
                'not_in:0',
                'distinct'
            ],
            'matches.*.match_type_id' => 'required|integer|not_in:0|exists:match_types,id',
            'matches.*.stipulations' => 'array|distinct',
            'matches.*.stipulations.*' => 'sometimes|integer|exists:stipulations,id',
            'matches.*.titles' => 'array|distinct',
            'matches.*.titles.*' => 'sometimes|integer|not_in:0|exists:titles,id',
            'matches.*.referees' => 'required|array|distinct',
            'matches.*.referees.*' => 'integer|not_in:0|exists:referees,id',
            'matches.*.wrestlers' => 'required|array|size:2|distinct',
            'matches.*.wrestlers.*' => [
                'bail',
                'integer',
                'not_in:0',
                'exists:wrestlers,id',
                new QualifiedForMatch($this->input('date'), '')
            ],
            'matches.*.preview' => 'required|string',
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
            'matches.*.match_type_id.required' => 'The match type is required.',
            'matches.*.match_type_id.not_in' => 'The selected match type is invalid.',
            'matches.*.stipulations.not_in' => 'The selected stipulation is invalid.',
            'matches.*.titles.not_in' => 'The selected title is invalid.',
            'matches.*.referees.required' => 'The referees field is required.',
            'matches.*.referees.not_in' => 'The selected referee is invalid.',
            'matches.*.preview.required' => 'The preview field is required.',
        ];
    }
}
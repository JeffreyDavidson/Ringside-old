<?php

namespace App\Http\Requests;

use App\Rules\QualifiedForMatch;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //dd($this->all());
        return [
            'name' => 'required|unique:events,name',
            'slug' => 'required|unique:events,slug',
            'date' => 'required|date',
            'venue_id' => 'required|integer|not_in:0|exists:venues,id',
            'matches.*.match_type_id' => 'required|integer|not_in:0|exists:match_types,id',
            'matches.*.stipulations' => 'array',
            'matches.*.titles' => 'array',
            'matches.*.referees' => 'required|array|not_in:0|exists:referees,id',
            'matches.*.wrestlers' => ['required', 'array', 'not_in:0', 'exists:wrestlers,id', new QualifiedForMatch()],
            'matches.*.preview' => 'required',
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
            'venue_id.not_in'  => 'The selected venue is invalid.',
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

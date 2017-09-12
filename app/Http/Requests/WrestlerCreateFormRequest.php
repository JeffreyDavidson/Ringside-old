<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WrestlerCreateFormRequest extends FormRequest
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
        return [
            'name' => 'required|unique:wrestlers,name',
            'slug' => 'required|unique:wrestlers,slug',
            'status_id' => [
                'required',
                'integer',
                'not_in:0',
                'exists:wrestler_statuses,id'
            ],
            'hometown' => 'required',
            'feet' => 'required|integer',
            'inches' => 'required|integer|max:11',
            'weight' => 'required|integer',
            'signature_move' => 'required',
            'hired_at' => 'required|date_format:m/d/Y',
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
            'status_id.not_in'  => 'The selected status is invalid.',
            'status_id.in'  => 'The selected status is invalid.',
        ];
    }
}

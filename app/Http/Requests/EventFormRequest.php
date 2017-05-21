<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
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
            'name' => 'required|unique:arenas,name',
            'slug' => 'required|unique:events,slug',
            'date' => 'required|date_format:"m/d/Y"',
            'arena_id' => 'required|not_in:0',
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
            'arena_id.required' => 'The arena field is required.',
            'arena_id.not_in'  => 'The selected arena is invalid.',
        ];
    }
}

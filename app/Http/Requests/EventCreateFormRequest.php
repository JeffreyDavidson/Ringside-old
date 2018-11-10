<?php

namespace App\Http\Requests;

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
        return [
            'name' => 'required|unique:events,name',
            'slug' => 'required|unique:events,slug',
            'date' => 'required|date',
            'venue_id' => 'required|integer|not_in:0|exists:venues,id',
            'number_of_matches' => 'required|integer|not_in:0',
            'schedule_matches' => 'required|boolean',
            'matches' => 'nullable|array|required_if:schedule_matches,1|min:1',
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
}

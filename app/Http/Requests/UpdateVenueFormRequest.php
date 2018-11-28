<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVenueFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $venue = $this->route('venue');

        return $this->user()->can('update', $venue);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'alpha_num_spaces', Rule::unique('venues', 'name')->ignore($this->venue->id)],
            'address' => ['required', 'string', 'alpha_num_spaces'],
            'city' => ['required', 'string', 'alpha_spaces'],
            'state' => ['required', 'string', 'alpha', 'size:2'],
            'postcode' => ['required', 'numeric', 'digits:5'],
        ];
    }
}

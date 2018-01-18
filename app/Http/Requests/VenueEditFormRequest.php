<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VenueEditFormRequest extends FormRequest
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
            'name'     => ['required', 'alpha_num_spaces', Rule::unique('venues', 'name')->ignore($this->venue->id)],
            'address'  => 'required|alpha_num_spaces',
            'city'     => 'required|alpha_spaces',
            'state'    => 'required|alpha|not_in:0',
            'postcode' => 'required|numeric|digits:5',
        ];
    }
}

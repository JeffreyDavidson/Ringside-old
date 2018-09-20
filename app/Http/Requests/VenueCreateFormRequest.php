<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenueCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('create-venue');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|alpha_num_spaces|unique:venues,name',
            'address' => 'required|alpha_num_spaces',
            'city' => 'required|alpha_spaces',
            'state' => 'required|alpha|not_in:0',
            'postcode' => 'required|numeric|digits:5',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Venue;
use Illuminate\Foundation\Http\FormRequest;

class StoreVenueFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Venue::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'alpha_num_spaces', 'unique:venues,name'],
            'address' => ['required', 'string', 'alpha_num_spaces'],
            'city' => ['required', 'string', 'alpha_spaces'],
            'state' => ['required', 'string', 'alpha', 'size:2'],
            'postcode' => ['required', 'numeric', 'digits:5'],
        ];
    }
}

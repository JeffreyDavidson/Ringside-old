<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StipulationCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->hasPermission('store-stipulation');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:stipulations,name',
            'slug' => 'required|unique:stipulations,slug'
        ];
    }
}

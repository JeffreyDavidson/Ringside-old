<?php

namespace App\Http\Requests;

use App\Models\Stipulation;
use Illuminate\Foundation\Http\FormRequest;

class StoreStipulationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Stipulation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:stipulations,name'],
            'slug' => ['required', 'string', 'unique:stipulations,slug'],
        ];
    }
}

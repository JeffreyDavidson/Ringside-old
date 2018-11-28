<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStipulationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $stipulation = $this->route('stipulation');

        return $this->user()->can('update', $stipulation);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('stipulations', 'name')->ignore($this->stipulation->id)],
            'slug' => ['required', 'string', Rule::unique('stipulations', 'slug')->ignore($this->stipulation->id)],
        ];
    }
}

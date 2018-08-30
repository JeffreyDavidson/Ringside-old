<?php

namespace App\Http\Requests;

use App\Models\Wrestler;
use Illuminate\Foundation\Http\FormRequest;

class WrestlerCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('store-wrestler');
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
            'hometown' => 'required',
            'feet' => 'required|integer',
            'inches' => 'required|integer|max:11',
            'weight' => 'required|integer',
            'signature_move' => 'required',
            'hired_at' => 'required|date',
        ];
    }

    public function prepareForValidation() {
        $this->offsetSet('height', ($this->input('feet') * 12) + $this->input('inches'));
    }
}

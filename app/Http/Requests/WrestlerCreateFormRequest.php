<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class WrestlerCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('create-wrestler');
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
            'signature_move' => 'required|unique:wrestlers,signature_move',
            'hired_at' => 'required|date',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isEmpty()) {
                $this->offsetSet('height', ($this->input('feet') * 12) + $this->input('inches'));
            }
        });
    }
}

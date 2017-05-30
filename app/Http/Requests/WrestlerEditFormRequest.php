<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WrestlerEditFormRequest extends FormRequest
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
            'name' => ['required', Rule::unique('wrestlers' ,'name')->ignore($this->wrestler->id)],
            'slug' => ['required', Rule::unique('wrestlers' ,'slug')->ignore($this->wrestler->id)],
            'status_id' => 'required|integer|not_in:0|exists:wrestler_statuses,id',
            'hometown' => 'required',
            'feet' => 'required|integer',
            'inches' => 'required|integer|max:11',
            'weight' => 'required|integer',
            'signature_move' => 'required',
            'hired_at' => 'required|date',
        ];
    }

    /**
     * Find out if the hired at date for the title is before the date of the first title's match.
     *
     */
    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            $attr = $validator->getData();
            if($this->wrestler->matches->isNotEmpty()) {
                $date = $this->wrestler->matches->first()->event->date;
                $hiredAt = \Carbon\Carbon::parse($attr['hired_at']);
                if($hiredAt >= $date) {
                    $sErr = 'The hired at date must be on or before '.$date->format('F d, Y').'.';
                    $validator->errors()->add('hired_at', $sErr);
                }
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'status_id.not_in'  => 'The selected status is invalid.',
        ];
    }
}

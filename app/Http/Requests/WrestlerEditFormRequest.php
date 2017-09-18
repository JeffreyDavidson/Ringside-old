<?php

namespace App\Http\Requests;

use App\Models\WrestlerStatus;
use App\Rules\BeforeFirstMatchDate;
use Carbon\Carbon;
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
        //dd($this->all());
        return [
            'name' => ['required', Rule::unique('wrestlers' ,'name')->ignore($this->wrestler->id)],
            'slug' => ['required', Rule::unique('wrestlers' ,'slug')->ignore($this->wrestler->id)],
            'status_id' => [
                'required',
                'integer',
                'not_in:0',
                'exists:wrestler_statuses,id',
                Rule::in(WrestlerStatus::available($this->wrestler->status(), false)->values()->toArray())
            ],
            'weight' => 'required|integer',
            'hometown' => 'required',
            'feet' => 'required|integer',
            'inches' => 'required|integer|max:11',
            'signature_move' => 'required',
            'hired_at' => ['required', 'date', new BeforeFirstMatchDate($this->wrestler)]
        ];
    }

    public function withValidator($validator)
    {
       //dd($validator->errors());
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
            'status_id.in'  => 'The selected status is invalid.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\WrestlerStatus;
use Illuminate\Validation\Rule;
use App\Rules\BeforeFirstMatchDate;
use Illuminate\Foundation\Http\FormRequest;

class WrestlerEditFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('update-wrestler');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('wrestlers', 'name')->ignore($this->wrestler->id)],
            'slug' => ['required', Rule::unique('wrestlers', 'slug')->ignore($this->wrestler->id)],
            'status' => [
                'required',
                'string',
                Rule::in(WrestlerStatus::available($this->wrestler->status)->pluck('id')->toArray())
            ],
            'weight' => 'required|integer',
            'hometown' => 'required',
            'feet' => 'required|integer',
            'inches' => 'required|integer|max:11',
            'signature_move' => 'required',
            'hired_at' => ['required', 'date', new BeforeFirstMatchDate($this->wrestler)]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'status_id.not_in' => 'The selected status is invalid.',
            'status_id.in' => 'The selected status is invalid.',
        ];
    }
}

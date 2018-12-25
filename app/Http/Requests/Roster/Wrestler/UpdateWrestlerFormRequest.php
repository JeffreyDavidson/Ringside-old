<?php

namespace App\Http\Requests\Roster\Wrestler;

use Illuminate\Validation\Rule;
use App\Rules\BeforeFirstMatchDate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWrestlerFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $wrestler = $this->route('wrestler');

        return $this->user()->can('update', $wrestler);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('wrestlers', 'name')->ignore($this->wrestler->id)],
            'slug' => ['required', 'string', Rule::unique('wrestlers', 'slug')->ignore($this->wrestler->id)],
            'hometown' => ['required', 'string'],
            'feet' => ['required', 'integer'],
            'inches' => ['required', 'integer', 'max:11'],
            'weight' => ['required', 'integer'],
            'signature_move' => ['required', 'string'],
            'hired_at' => ['required', 'string', 'date', new BeforeFirstMatchDate($this->wrestler)],
        ];
    }
}

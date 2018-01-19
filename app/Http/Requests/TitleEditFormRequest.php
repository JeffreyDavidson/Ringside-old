<?php

namespace App\Http\Requests;

use App\Rules\BeforeFirstMatchDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TitleEditFormRequest extends FormRequest
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
            'name' => ['required', Rule::unique('titles' ,'name')->ignore($this->title->id)],
            'slug' => ['required', Rule::unique('titles' ,'slug')->ignore($this->title->id)],
            'introduced_at' => ['required', 'date', new BeforeFirstMatchDate($this->title)],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\BeforeFirstMatchDate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTitleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $title = $this->route('title');

        return $this->user()->can('update', $title);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('titles', 'name')->ignore($this->title->id)],
            'slug' => ['required', 'string', Rule::unique('titles', 'slug')->ignore($this->title->id)],
            'introduced_at' => ['required', 'string', 'date', new BeforeFirstMatchDate($this->title)],
        ];
    }
}

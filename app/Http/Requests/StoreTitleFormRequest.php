<?php

namespace App\Http\Requests;

use App\Models\Title;
use Illuminate\Foundation\Http\FormRequest;

class StoreTitleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Title::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:titles,name'],
            'slug' => ['required', 'string', 'unique:titles,slug'],
            'introduced_at' => ['required', 'string', 'date'],
        ];
    }
}

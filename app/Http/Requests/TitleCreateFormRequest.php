<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitleCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('store-title');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:titles,name',
            'slug' => 'required|unique:titles,slug',
            'introduced_at' => 'required|date'
        ];
    }
}

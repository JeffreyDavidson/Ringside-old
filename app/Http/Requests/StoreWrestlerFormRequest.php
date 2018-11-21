<?php

namespace App\Http\Requests;

use App\Models\Wrestler;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreWrestlerFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Wrestler::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:wrestlers,name'],
            'slug' => ['required', 'string', 'unique:wrestlers,slug'],
            'hometown' => ['required', 'string'],
            'feet' => ['required', 'integer'],
            'inches' => ['required', 'integer', 'max:11'],
            'weight' => ['required', 'integer'],
            'signature_move' => ['required', 'string'],
            'hired_at' => ['required', 'string', 'date'],
        ];
    }
}

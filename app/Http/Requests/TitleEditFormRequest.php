<?php

namespace App\Http\Requests;

use Carbon\Carbon;
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
            'name'          => ['required', Rule::unique('titles', 'name')->ignore($this->title->id)],
            'slug'          => ['required', Rule::unique('titles', 'slug')->ignore($this->title->id)],
            'introduced_at' => 'required|date',
        ];
    }

    /**
     * Find out if the introduced at date for the title is before the date of the first title's match.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $attr = $validator->getData();

            if (!$this->title->hasPastMatches()) {
                return;
            }

            $firstMatchDate = $this->title->firstMatchDate();
            $introducedAt = Carbon::parse($attr['introduced_at']);

            if ($introducedAt->lte($firstMatchDate)) {
                return;
            }

            $sErr = 'The introduced at date must be on or before '.$firstMatchDate->format('F d, Y').'.';
            $validator->errors()->add('introduced_at', $sErr);
        });
    }
}

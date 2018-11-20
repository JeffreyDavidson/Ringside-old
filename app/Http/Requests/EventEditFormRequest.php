<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EventEditFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $event = $this->route('event');

        return $this->user()->can('update', $event);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        dd($this->event->id);
        return [
            'name' => ['required', Rule::unique('events', 'name')->ignore($this->event->id)],
            'slug' => ['required', Rule::unique('events', 'slug')->ignore($this->event->id)],
            'date' => 'required|date',
            'venue_id' => 'required|integer|not_in:0|exists:venues,id,deleted_at,NULL',
        ];
    }
}

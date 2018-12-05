<?php

namespace App\Http\Requests\Roster\TagTeam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagTeamFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tagteam = $this->route('tagteam');

        dd($tagteam);
        return $this->user()->can('update', $tagteam);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:tag_teams,name'],
            'slug' => ['required', 'string', 'unique:tag_teams,slug'],
            'signature_move' => ['required', 'string', 'unique:tag_teams,signature_move'],
            'hired_at' => ['required', 'string', 'date'],
            'wrestlers' => ['required', 'array', 'size:2'],
            'wrestlers.*' => ['integer', 'exists:wrestlers,id'],   
        ];
    }
}

<?php

namespace App\Http\Requests\Roster\TagTeam;

use App\Models\Roster\TagTeam;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTagTeamFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', TagTeam::class);
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

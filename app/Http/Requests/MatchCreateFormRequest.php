<?php

namespace App\Http\Requests;

use App\Models\MatchType;
use App\Models\Wrestler;
use App\Models\Event;
use App\Rules\QualifiedForMatch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MatchCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission('store-match');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $date = request()->event->date;

        $validateWrestlerNumbers = function ($attribute, $match, $fail) {
            if (is_null($match['match_type_id'])) return;

            // if we get no competitors the match type id is wrong which will get caught below
            $competitors = (int) MatchType::whereKey($match['match_type_id'])->value('total_competitors');

            if (!$competitors) return;

            // Wrestlers input is not valid format, will get caught below
            if(!isset($match['wrestlers']) || !is_array($match['wrestlers'])) return;

            //now we just flatten our wrestlers again, and count them
            $flattened = array_flatten($match['wrestlers']);

            if (count($flattened) !== $competitors) {
                $matchIndex = explode('.', $attribute)[1];
                $fail("Match {$matchIndex} must have {$competitors} competitors");
            }
        };

        $validateWrestlersUnique = function ($attribute, $value, $fail) {
            $flattened = array_flatten($value);
            if (count(array_unique($flattened)) !== count($flattened)) {
                $matchIndex = explode('.', $attribute)[1];
                $fail("Match {$matchIndex} has duplicate ids");
            }
        };

        $validateQualifiedForMatch = new QualifiedForMatch($date, Wrestler::class, 'hired_at');

        $rules = [
            'matches'                  => ['array'],
            'matches.*'                => [$validateWrestlerNumbers],
            'matches.*.match_type_id'  => ['required', 'integer', Rule::exists('match_types', 'id')],
            'matches.*.stipulation_id' => ['nullable', 'integer', Rule::exists('stipulations', 'id')],
            'matches.*.titles'         => ['array'],
            'matches.*.titles.*'       => ['sometimes', 'distinct', 'integer', Rule::exists('titles', 'id')],
            'matches.*.referees'       => ['required', 'array'],
            'matches.*.referees.*'     => ['distinct', 'integer', Rule::exists('referees', 'id')],
            'matches.*.preview'        => ['required', 'string'],
            'matches.*.wrestlers'      => ['bail', 'array', 'required', $validateWrestlersUnique],
            'matches.*.wrestlers.*.*'  => ['integer', 'exists:wrestlers,id', $validateQualifiedForMatch],
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'venue_id.required' => 'The venue field is required.',
            'venue_id.not_in' => 'The selected venue is invalid.',
            'matches.*.match_type_id.required' => 'The match type is required.',
            'matches.*.match_type_id.not_in' => 'The selected match type is invalid.',
            'matches.*.titles.not_in' => 'The selected title is invalid.',
            'matches.*.referees.required' => 'The referees field is required.',
            'matches.*.referees.not_in' => 'The selected referee is invalid.',
            'matches.*.preview.required' => 'The preview field is required.',
        ];
    }
}
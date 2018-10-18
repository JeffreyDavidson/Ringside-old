{{ csrf_field() }}

{{ Form::bsSelect('match_type_id', $matchTypes, old('match_type_id', $match->match_type_id), [], true, 'Match Type') }}
{{ Form::bsSelect('stipulation_id', $stipulations, old('stipulation_id', $match->stipulation_id), [], true, 'Stipulation') }}
{{ Form::bsSelect('titles[]', $titles, old('titles[]', $match->titles->modelKeys()), [], true, 'Titles', null, true) }}
{{ Form::bsSelect('referees[]', $referees, old('referees[]', $match->referees->modelKeys()), [], true, 'Referees', null, true) }}
{{ Form::bsSelect('wrestlers[]', $wrestlers, old('wrestlers[]', $match->wrestlers->modelKeys()), [], true, 'Wrestlers', null, true) }}
{{ Form::bsTextarea('preview', old('preview', $match->preview), [], true, 'Preview') }}
{{ Form::bsSubmit($submitButtonText ?? 'Schedule Match') }}

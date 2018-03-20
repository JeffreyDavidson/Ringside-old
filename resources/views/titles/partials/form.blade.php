{{ csrf_field() }}

{{ Form::bsText('name', old('name', $title->name), [], true, 'Name') }}
{{ Form::bsText('slug', old('name', $title->slug), [], true, 'Slug') }}
{{ Form::bsDate('introduced_at', old('introduced_at', optional($title->introduced_at)->format('m/d/Y')), [], true, 'Date Introduced') }}
{{ Form::bsSubmit($submitButtonText ?? 'Create Title') }}


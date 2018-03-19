{{ csrf_field() }}

{{ Form::bsText('name', old('name', $stipulation->name), [], true, 'Name') }}
{{ Form::bsText('slug', old('slug', $stipulation->slug), [], true, 'Slug') }}
{{ Form::bsSubmit($submitButtonText ?? 'Create Stipulation') }}

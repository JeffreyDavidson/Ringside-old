{{ csrf_field() }}

{{ Form::bsText('name', old('name', $event->name), [], true, 'Name') }}
{{ Form::bsText('slug', old('slug', $event->slug), [], true, 'Slug') }}
{{ Form::bsDate('date', old('date') ?? $event->introduced_at, [], true, 'Date') }}
{{ Form::bsSelect('venue_id', App\Models\Venue::all(), old('venue_id', $event->venue_id), [], true, 'Venue') }}
{{ Form::bsSubmit($submitButtonText ?? 'Create Event') }}

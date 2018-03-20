{{ csrf_field() }}

{{ Form::bsText('name', old('name', $event->name), [], true, 'Name') }}
{{ Form::bsText('slug', old('slug', $event->slug), [], true, 'Slug') }}
{{ Form::bsDate('date', old('date', optional($event->date)->format('m/d/Y')), [], true, 'Date') }}
{{ Form::bsSelect('venue_id', App\Models\Venue::pluck('name', 'id'), old('venue_id', $event->venue_id), [], true, 'Venue') }}
{{ Form::bsSubmit($submitButtonText ?? 'Create Event') }}

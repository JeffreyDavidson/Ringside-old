{{ csrf_field() }}

{{ Form::bsText('name', old('name', $event->name), [], true, 'Name') }}
{{ Form::bsText('slug', old('slug', $event->slug), [], true, 'Slug') }}
{{ Form::bsDate('date', old('date', optional($event->date)->format('Y-m-d')), [], true, 'Date') }}
{{ Form::bsSelect('venue_id', App\Models\Venue::pluck('name', 'id'), old('venue_id', $event->venue_id), [], true, 'Venue') }}
{{ Form::bsText('number_of_matches', old('number_of_matches', $event->number_of_matches), [], true, 'Number of Matches') }}

@if (request()->route()->getName() == 'events.create')
    <div class="form-group @if ($errors->has('schedule_matches')) {{ 'has-danger' }} @endif">
        <label class="form-control-label">Schedule Matches?</label>
        {{ Form::bsRadio('schedule_matches', 'Yes')}}
        {{ Form::bsRadio('schedule_matches', 'No')}}
        @if ($errors->has('schedule_matches')) <small class="form-control-feedback">{{ $errors->first('schedule_matches') }}</small> @endif
    </div>
@endif

{{ Form::bsSubmit($submitButtonText ?? 'Create Event') }}

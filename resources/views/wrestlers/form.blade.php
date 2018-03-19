{{ csrf_field() }}

{{ Form::bsText('name', old('name', $wrestler->name), [], true, 'Name') }}
{{ Form::bsText('slug', old('slug', $wrestler->slug), [], true, 'Slug') }}
{{ Form::bsSelect('status_id', $wrestler->availableStatuses()->pluck('name', 'id'), old('status_id', $wrestler->status_id), [], true, 'Status') }}
{{ Form::bsText('hometown', old('hometown', $wrestler->hometown), [], true, 'Hometown') }}

<div class="row">
    <div class="col-sm-4">
        {{ Form::bsText('feet', old('feet', $wrestler->present()->height_in_feet()), [], true, 'Height', true, 'feet') }}
    </div>

    <div class="col-sm-4">
        {{ Form::bsText('inches', old('inches', $wrestler->present()->height_in_inches()), [], true, null, true, 'inches') }}
    </div>

    <div class="col-sm-4">
        {{ Form::bsText('weight', old('weight', $wrestler->weight), [], true, 'Weight', true, 'lbs.') }}
    </div>
</div>

{{ Form::bsText('signature_move', old('signature_move', $wrestler->signature_move), [], true, 'Signature Move') }}
{{ Form::bsDate('hired_at', old('hired_at') ?? $wrestler->hired_at, [], true, 'Date Hired') }}
{{ Form::bsSubmit($submitButtonText ?? 'Create Wrestler') }}

{{ csrf_field() }}

{{ Form::bsText('name', old('name', $venue->name), [], true, 'Name') }}
{{ Form::bsText('address', old('address', $venue->address), [], true, 'Address') }}

<div class="row">
    <div class="col-md-6">
        {{ Form::bsText('city', old('city', $venue->city), [], true, 'City') }}
    </div>

    <div class="col-md-3">
        {{ Form::bsSelect('state', App\Http\Utilities\State::options(), old('state', $venue->state), [], true, 'State') }}
    </div>

    <div class="col-md-3">
        {{ Form::bsText('postcode', old('postcode', $venue->postcode), [], true, 'Postcode') }}
    </div>
</div>

{{ Form::bsSubmit($submitButtonText ?? 'Create Venue') }}

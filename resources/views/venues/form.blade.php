{{ csrf_field() }}

<div class="form-group @if ($errors->has('name')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $venue->name }}"/>
    @if ($errors->has('name')) <small class="form-control-feedback">{{ $errors->first('name') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('address')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="address">Address</label>
    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') ?? $venue->address }}"/>
    @if ($errors->has('name')) <small class="form-control-feedback">{{ $errors->first('address') }}</small> @endif
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('city')) {{ 'has-danger' }} @endif">
            <label class="form-control-label" for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') ?? $venue->city }}"/>
            @if ($errors->has('name')) <small class="form-control-feedback">{{ $errors->first('city') }}</small> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if ($errors->has('state')) {{ 'has-danger' }} @endif">
            <label class="form-control-label" for="state">State</label>
            <select name="state" class="form-control" id="state" value="{{ old('state') ?? $venue->state }}">
                <option value="0">Choose One</option>
                @foreach(App\Http\Utilities\State::all() as $state)
                    <option value="value" @if($state == (old('state') ?? $venue->state)) selected @endif>{{$state}}</option>
                @endforeach
            </select>
            @if ($errors->has('name')) <small class="form-control-feedback">{{ $errors->first('state') }}</small> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if ($errors->has('postcode')) {{ 'has-danger' }} @endif">
            <label class="form-control-label" for="postcode">Postcode</label>
            <input type="text" class="form-control" id="postcode" name="postcode" value="{{ old('postcode') ?? $venue->postcode }}"/>
            @if ($errors->has('postcode')) <small class="form-control-feedback">{{ $errors->first('postcode') }}</small> @endif
        </div>
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Venue' }}
    </button>
</div>

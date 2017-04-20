{{ csrf_field() }}

<div class="form-group @if ($errors->has('name')) {{ 'has-error' }} @endif">
    <label class="control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $arena->name }}"/>
    @if ($errors->has('name')) <small class="help-block">{{ $errors->first('name') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('address')) {{ 'has-error' }} @endif">
    <label class="control-label" for="address">Address</label>
    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') ?? $arena->address }}"/>
    @if ($errors->has('name')) <small class="help-block">{{ $errors->first('address') }}</small> @endif
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('city')) {{ 'has-error' }} @endif">
            <label class="control-label" for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') ?? $arena->city }}"/>
            @if ($errors->has('name')) <small class="help-block">{{ $errors->first('city') }}</small> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if ($errors->has('state')) {{ 'has-error' }} @endif">
            <label class="control-label" for="state">State</label>
            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') ?? $arena->state }}"/>
            @if ($errors->has('name')) <small class="help-block">{{ $errors->first('state') }}</small> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if ($errors->has('postcode')) {{ 'has-error' }} @endif">
            <label class="control-label" for="postcode">Postcode</label>
            <input type="text" class="form-control" id="postcode" name="postcode" value="{{ old('postcode') ?? $arena->postcode }}"/>
            @if ($errors->has('postcode')) <small class="help-block">{{ $errors->first('postcode') }}</small> @endif
        </div>
    </div>
</div>

@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Arena' }}
    </button>
</div>

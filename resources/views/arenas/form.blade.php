{{ csrf_field() }}

<div class="form-group">
    <label class="form-control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $arena->name }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="address">Address</label>
    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') ?? $arena->address }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="city">City</label>
    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') ?? $arena->city }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="state">State</label>
    <input type="text" class="form-control" id="state" name="postcode" value="{{ old('state') ?? $arena->state }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="postcode">Postcode</label>
    <input type="text" class="form-control" id="postcode" name="postcode" value="{{ old('postcode') ?? $arena->postcode }}"/>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Arena' }}
    </button>
</div>
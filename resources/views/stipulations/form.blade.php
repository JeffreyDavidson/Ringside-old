{{ csrf_field() }}

<div class="form-group @if ($errors->has('name')) {{ 'has-error' }} @endif">
    <label class="control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $stipulation->name }}"/>
    @if ($errors->has('name')) <small class="help-block">{{ $errors->first('name') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('slug')) {{ 'has-error' }} @endif">
    <label class="control-label" for="slug">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') ?? $stipulation->slug }}"/>
    @if ($errors->has('slug')) <small class="help-block">{{ $errors->first('slug') }}</small> @endif
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Stipulation' }}
    </button>
</div>

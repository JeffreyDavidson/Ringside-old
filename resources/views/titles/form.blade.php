{{ csrf_field() }}

<div class="form-group @if ($errors->has('name')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $title->name }}"/>
    @if ($errors->has('name')) <small class="form-control-feedback">{{ $errors->first('name') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('slug')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="slug">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') ?? $title->slug }}"/>
    @if ($errors->has('slug')) <small class="form-control-feedback">{{ $errors->first('slug') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('introduced_at')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="introduced_at">Date Introduced</label>
    <div class="input-group">
        <span class="input-group-addon">
            <i class="icon wb-calendar" aria-hidden="true"></i>
        </span>
        <input type="date" data-plugin="datepicker" class="form-control datepicker" id="introduced_at" name="introduced_at" value="{{ old('introduced_at') ?? ($title->introduced_at ?: \Carbon\Carbon::now())->format('Y-m-d') }}"/>
    </div>
    @if ($errors->has('introduced_at')) <small class="form-control-feedback">{{ $errors->first('introduced_at') }}</small> @endif
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Title' }}
    </button>
</div>

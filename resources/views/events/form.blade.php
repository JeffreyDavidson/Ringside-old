{{ csrf_field() }}

<div class="form-group @if ($errors->has('name')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $event->name }}"/>
    @if ($errors->has('name')) <small class="form-control-feedback">{{ $errors->first('name') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('slug')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="slug">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') ?? $event->slug }}"/>
    @if ($errors->has('slug')) <small class="form-control-feedback">{{ $errors->first('slug') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('date')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="date">Date</label>
    <div class="input-group date">
        <span class="input-group-addon">
            <i class="icon wb-calendar" aria-hidden="true"></i>
        </span>
        <input type="date" data-plugin="datepicker" class="form-control" id="date" name="date" value="{{ old('date') ?? ($event->date ?: \Carbon\Carbon::now())->format('Y-m-d') }}"/>
    </div>
    @if ($errors->has('date')) <small class="form-control-feedback">{{ $errors->first('date') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('venue_id')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="venue_id">Venue</label>
    <select class="form-control" id="venue_id" name="venue_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\Venue::all() as $venue)
            <option value="{{ $venue->id }}" {{ $event->venue_id == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('venue_id')) <small class="form-control-feedback">{{ $errors->first('venue_id') }}</small> @endif
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Event' }}
    </button>
</div>

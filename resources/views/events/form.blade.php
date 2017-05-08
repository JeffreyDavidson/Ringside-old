{{ csrf_field() }}

<div class="form-group @if ($errors->has('name')) {{ 'has-error' }} @endif">
    <label class="control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $event->name }}"/>
    @if ($errors->has('name')) <small class="help-block">{{ $errors->first('name') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('slug')) {{ 'has-error' }} @endif">
    <label class="control-label" for="slug">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') ?? $event->slug }}"/>
    @if ($errors->has('slug')) <small class="help-block">{{ $errors->first('slug') }}</small> @endif
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('date')) {{ 'has-error' }} @endif">
            <label class="control-label" for="date">Date</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="icon wb-calendar" aria-hidden="true"></i>
                </span>
                <input type="date" data-plugin="datepicker" class="form-control" id="date" name="date" value="{{ old('date') ?? $event->formatted_form_date }}"/>
            </div>
            @if ($errors->has('date')) <small class="help-block">{{ $errors->first('date') }}</small> @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('time')) {{ 'has-error' }} @endif">
            <label class="control-label" for="time">Bell Time</label>
            <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon wb-time" aria-hidden="true"></i>
                </span>
                <input type="text" id="time" name="time" class="form-control" data-plugin="timepicker" value="{{ old('time') ?? $event->time }}"/>
            </div>
        </div>
        @if ($errors->has('time')) <small class="help-block">{{ $errors->first('time') }}</small> @endif
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="arena_id">Arena</label>
    <select class="form-control" id="arena_id" name="arena_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\Arena::all() as $arena)
            <option value="{{ $arena->id }}" {{ $event->arena_id == $arena->id ? 'selected' : '' }}>{{ $arena->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('arena_id')) <small class="help-block">{{ $errors->first('arena_id') }}</small> @endif
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Event' }}
    </button>
</div>

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

<div class="form-group @if ($errors->has('date')) {{ 'has-error' }} @endif">
    <label class="control-label" for="date">Date</label>
    <div class="input-group date">
        <span class="input-group-addon">
            <i class="icon wb-calendar" aria-hidden="true"></i>
        </span>
        <input type="date" data-plugin="datetimepicker" class="form-control" id="date" name="date" value="{{ old('date') ?? $event->formatted_form_date }}"/>
    </div>
    @if ($errors->has('date')) <small class="help-block">{{ $errors->first('date') }}</small> @endif
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

<h2>Match #1</h2>
<div class="form-group">
    <label class="form-control-label" for="match_type_id">Match Type</label>
    <select class="form-control" id="match_type_id" name="match_type_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\MatchType::all() as $type)
            <option value="{{ $type->id }}" {{ $event->arena_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('match_type_id')) <small class="help-block">{{ $errors->first('match_type_id') }}</small> @endif
</div>

<div class="form-group">
    <label class="form-control-label" for="stipulation_id">Match Stipulation</label>
    <select class="form-control" id="stipulation_id" name="stipulation_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\Stipulation::all() as $stipulation)
            <option value="{{ $stipulation->id }}" {{ $event->arena_id == $stipulation->id ? 'selected' : '' }}>{{ $stipulation->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('stipulation_id')) <small class="help-block">{{ $errors->first('stipulation_id') }}</small> @endif
</div>

<div class="form-group">
    <label class="form-control-label" for="referee_id">Referee</label>
    <select class="form-control" id="referee_id" name="referee_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\Referee::all() as $referee)
            <option value="{{ $referee->id }}" {{ $event->arena_id == $referee->id ? 'selected' : '' }}>{{ $referee->first_name }} {{ $referee->last_name }}</option>
        @endforeach
    </select>
    @if ($errors->has('referee')) <small class="help-block">{{ $errors->first('referee') }}</small> @endif
</div>

<div class="form-group">
    <label class="form-control-label" for="preview">Preview</label>
    <textarea class="form-control" id="preview" name="preview" rows="5"></textarea>
    @if ($errors->has('referee')) <small class="help-block">{{ $errors->first('referee') }}</small> @endif
</div>


<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Event' }}
    </button>
</div>

<div class="form-group">
    <label class="form-control-label" for="match_type_id">Match Type</label>
    <select class="form-control" id="match_type_id" name="match_type_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\MatchType::all() as $type)
            <option value="{{ $type->id }}" {{ $match->match_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('match_type_id')) <small class="help-block">{{ $errors->first('match_type_id') }}</small> @endif
</div>

<div class="form-group">
    <label class="form-control-label" for="stipulation_id">Match Stipulation</label>
    <select class="form-control" id="stipulation_id" name="stipulation_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\Stipulation::all() as $stipulation)
            <option value="{{ $stipulation->id }}" {{ $match->stipulation_id == $stipulation->id ? 'selected' : '' }}>{{ $stipulation->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('stipulation_id')) <small class="help-block">{{ $errors->first('stipulation_id') }}</small> @endif
</div>

<div class="form-group">
    <label class="form-control-label" for="referee_id">Referee</label>
    <select class="form-control" id="referee_id" name="referee_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\Referee::all() as $referee)
            <option value="{{ $referee->id }}" {{ $match->referee_id == $referee->id ? 'selected' : '' }}>{{ $referee->first_name }} {{ $referee->last_name }}</option>
        @endforeach
    </select>
    @if ($errors->has('referee')) <small class="help-block">{{ $errors->first('referee') }}</small> @endif
</div>

<div class="form-group">
    <label class="form-control-label" for="wrestlers">Wrestlers</label>
    <select class="form-control" id="wrestlers" name="wrestlers[]" multiple="multiple">
        <option value="0">Choose At Least Two</option>
        @foreach(App\Models\Wrestler::all() as $wrestler)
            <option value="{{ $wrestler->id }}">{{ $wrestler->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('wrestlers[]')) <small class="help-block">{{ $errors->first('wrestlers[]') }}</small> @endif
</div>

<div class="form-group">
    <label class="form-control-label" for="preview">Preview</label>
    <textarea class="form-control" id="preview" name="preview" rows="5">{{  $match->preview }}</textarea>
    @if ($errors->has('referee')) <small class="help-block">{{ $errors->first('referee') }}</small> @endif
</div>
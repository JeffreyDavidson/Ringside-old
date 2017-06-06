<div class="form-group @if ($errors->has('matches.*.match_type_id')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="match_type_id">Match Type</label>
    <select class="form-control" id="match_type_id" name="matches[{{ $x }}][match_type_id]" placeholder="Choose One">
        <option value="0">Choose One</option>
        @foreach(App\Models\MatchType::all() as $type)
            <option value="{{ $type->id }}" {{ $match->match_type_id == $type->id || old('matches.'.$x.'.match_type_id') == $type->id ? 'selected="selected"' : '' }}>{{ $type->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('matches.*.match_type_id')) <small class="form-control-feedback">{{ $errors->first('matches.*.match_type_id') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('matches.*.stipulations')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="stipulations">Match Stipulation</label>
    <select class="form-control" id="stipulations" name="matches[{{ $x }}][stipulations][]" multiple="multiple">
        <option value="0" disabled="disabled">Please Choose At Least One</option>
        @foreach(App\Models\Stipulation::all() as $stipulation)
            <option value="{{ $stipulation->id }}" @if(collect(old("matches.$x.stipulations"))->contains($stipulation->id)) selected @endif>{{ $stipulation->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('matches.*.stipulations')) <small class="form-control-feedback">{{ $errors->first('matches.*.stipulations') }}</small> @endif
</div>

<div class="form-group  @if ($errors->has('matches.*.titles')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="titles">Titles</label>
    <select class="form-control" id="titles" name="matches[{{ $x }}][titles][]" multiple="multiple">
        <option value="0" selected="selected">None</option>
        @foreach(App\Models\Title::all() as $title)
            <option value="{{ $title->id }}" {{ $match->title_id == $title->id ? 'selected' : '' }}>{{ $title->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('matches.*.titles')) <small class="form-control-feedback">{{ $errors->first('matches.*.titles') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('matches.*.referees')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="referees">Referees</label>
    <select class="form-control" id="referees" name="matches[{{ $x }}][referees][]" multiple="multiple" placeholder="Choose One">
        @foreach(App\Models\Referee::all() as $referee)
            <option value="{{ $referee->id }}" {{ $match->referee_id == $referee->id ? 'selected="selected"' : '' }}>{{ $referee->first_name }} {{ $referee->last_name }}</option>
        @endforeach
    </select>
    @if ($errors->has('matches.*.referees')) <small class="form-control-feedback">{{ $errors->first('matches.*.referees') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('matches.*.wrestlers')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="wrestlers">Wrestlers</label>
    <select class="form-control" id="wrestlers" name="matches[{{ $x }}][wrestlers][]" multiple="multiple" placeholder="Choose At Least Two">
        @foreach(App\Models\Wrestler::all() as $wrestler)
            <option value="{{ $wrestler->id }}">{{ $wrestler->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('matches.*.wrestlers')) <small class="form-control-feedback">{{ $errors->first('matches.*.wrestlers') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('matches.*.preview')) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="preview">Preview</label>
    <textarea class="form-control" id="preview" name="matches[{{ $x }}][preview]" rows="5">{{  $match->preview }}</textarea>
    @if ($errors->has('matches.*.preview')) <small class="form-control-feedback">{{ $errors->first('matches.*.preview') }}</small> @endif
</div>

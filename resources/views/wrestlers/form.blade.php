{{ csrf_field() }}
{{--{{ dd($wrestler) }}--}}
<div class="form-group @if ($errors->has('name')) {{ 'has-error' }} @endif">
    <label class="form-control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $wrestler->name }}"/>
    @if ($errors->has('name')) <small class="help-block">{{ $errors->first('name') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('slug')) {{ 'has-error' }} @endif">
    <label class="form-control-label" for="slug">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') ?? $wrestler->slug }}"/>
    @if ($errors->has('slug')) <small class="help-block">{{ $errors->first('slug') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('status_id')) {{ 'has-error' }} @endif">
    <label class="form-control-label" for="status_id">Status</label>
    <select class="form-control" id="status_id" name="status_id">
        <option value="0">Choose One</option>
        @foreach(App\Models\WrestlerStatus::all() as $status)
            <option value="{{ $status->id }}" {{ ($wrestler->status_id == $status->id) ? 'selected="selected"' : '' }}>{{ $status->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('status_id')) <small class="help-block">{{ $errors->first('status_id') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('hometown')) {{ 'has-error' }} @endif">
    <label class="form-control-label" for="slug">Hometown</label>
    <input type="text" class="form-control" id="hometown" name="hometown" value="{{ old('hometown') ?? $wrestler->bio->hometown ?? ''}}"/>
    @if ($errors->has('hometown')) <small class="help-block">{{ $errors->first('hometown') }}</small> @endif
</div>

<div class="row">
    <div class="col-sm-6">
        <label class="form-control-label" for="height">Height</label>
        <div class="row">
            <div class="form-group col-sm-6 @if ($errors->has('feet')) {{ 'has-error' }} @endif">
                <div class="input-group">
                    <input type="text" class="form-control" id="feet" name="feet" value="{{ old('feet') ?? $wrestler->height_in_feet }}"/>
                    <span class="input-group-addon">feet</span>
                </div>
                @if ($errors->has('feet')) <small class="help-block">{{ $errors->first('feet') }}</small> @endif
            </div>
            <div class="form-group col-sm-6 @if ($errors->has('inches')) {{ 'has-error' }} @endif">
                <div class="input-group">
                    <input type="text" class="form-control" id="inches" name="inches" value="{{ old('inches') ?? $wrestler->height_in_inches }}"/>
                    <span class="input-group-addon">inches</span>
                </div>
                @if ($errors->has('inches')) <small class="help-block">{{ $errors->first('inches') }}</small> @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <label class="form-control-label" for="weight">Weight</label>
        <div class="form-group @if ($errors->has('weight')) {{ 'has-error' }} @endif">
            <div class="input-group">
                <input type="text" class="form-control" id="weight" name="weight" value="{{ old('weight') ?? $wrestler->bio->weight ?? '' }}"/>
                <span class="input-group-addon">lbs.</span>
            </div>
            @if ($errors->has('weight')) <small class="help-block">{{ $errors->first('weight') }}</small> @endif
        </div>
    </div>
</div>

<div class="form-group @if ($errors->has('signature_move')) {{ 'has-error' }} @endif">
    <label class="form-control-label" for="signature_move">Signature Move</label>
    <input type="text" class="form-control" id="signature_move" name="signature_move" value="{{ old('signature_move') ?? $wrestler->bio->signature_move ?? '' }}"/>
    @if ($errors->has('signature_move')) <small class="help-block">{{ $errors->first('signature_move') }}</small> @endif
</div>

<div class="form-group @if ($errors->has('hired_at')) {{ 'has-error' }} @endif">
    <label class="control-label" for="hired_at">Date Hired</label>
    <div class="input-group">
        <span class="input-group-addon">
            <i class="icon wb-calendar" aria-hidden="true"></i>
        </span>
        <input type="date" data-plugin="datepicker" class="form-control" id="hired_at" name="hired_at" value="{{ old('hired_at') ?? ($wrestler->hired_at ?: \Carbon\Carbon::now())->format('m/d/Y') }}"/>
    </div>
    @if ($errors->has('hired_at')) <small class="help-block">{{ $errors->first('hired_at') }}</small> @endif
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Wrestler' }}
    </button>
</div>
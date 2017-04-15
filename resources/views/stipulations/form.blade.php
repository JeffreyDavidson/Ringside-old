{{ csrf_field() }}

<div class="form-group">
    <label class="form-control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $wrestler->name }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="slug">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') ?? $wrestler->slug }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="status_id">Status</label>
    <select class="form-control" id="status_id" name="status_id">
        <option value="0">Choose One</option>
        @foreach(App\Status::all() as $status)
            <option value="{{ $status->id }}" {{ $wrestler->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="form-control-label" for="slug">Hometown</label>
    <input type="text" class="form-control" id="hometown" name="hometown" value="{{ old('hometown') ?? $wrestler->bio->hometown }}"/>
</div>

<label class="form-control-label" for="height">Height</label>
<div class="row">
    <div class="form-group col-sm-3">
        <div class="input-group">
            <input type="text" class="form-control" id="feet" name="feet" value="{{ old('feet') ?? $wrestler->feet }}"/>
            <span class="input-group-addon">feet</span>
        </div>
    </div>
    <div class="form-group col-sm-3">
        <div class="input-group">
            <input type="text" class="form-control" id="inches" name="inches" value="{{ old('inches') ?? $wrestler->inches }}"/>
            <span class="input-group-addon">inches</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="form-control-label" for="slug">Weight</label>
        <div class="input-group">
            <input type="text" class="form-control" id="weight" name="weight" value="{{ old('weight') ?? $wrestler->bio->weight }}"/>
            <span class="input-group-addon">lbs.</span>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="signature_move">Signature Move</label>
    <input type="text" class="form-control" id="signature_move" name="signature_move" value="{{ old('signature_move') ?? $wrestler->bio->signature_move }}"/>
</div>

<div class="form-group">
    <button type="button" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Wrestler' }}
    </button>
</div>
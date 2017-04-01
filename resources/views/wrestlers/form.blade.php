{{ csrf_field() }}

<div class="form-group">
    <label class="form-control-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name"
           placeholder="Name" autocomplete="off" value="{{ old('name') ?? $wrestler->name }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="slug">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug" autocomplete="off" value="{{ old('slug') ?? $wrestler->slug }}"/>
</div>

<div class="form-group">
    <label class="form-control-label" for="status_id">Status</label>
    <select class="form-control" id="status_id" name="status_id">
        <option value="0">Choose One</option>
        @foreach(App\WrestlerStatus::all() as $status)
            <option value="{{ $status->id }}" {{ $wrestler->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <button type="button" class="btn btn-primary">
        {{ $submitButtonText ?? 'Create Wrestler' }}
    </button>
</div>
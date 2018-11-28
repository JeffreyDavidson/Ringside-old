<div class="form-group @if ($errors->has($name)) {{ 'has-danger' }} @endif">
    <label class="form-control-label" for="{{ $name }}">{{ $label }}</label>
    <select class="form-control" id="{{ $name }}" name="{{ $name }}" {{ $multiple ? 'multiple' : '' }}>
        <option value="">Choose One</option>
        @foreach ($options as $key => $value)
            <option value="{{ $key }}"{{ $selected == $key ? ' selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>
    @if ($errors->has($name)) <small class="form-control-feedback">{{ $errors->first($name) }}</small> @endif
</div>
{{-- <div class="form-group">
    <div class="form-check @if ($errors->has($name)) {{ 'has-danger' }} @endif">
        <input type="checkbox" class="form-check-input" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"/>
        <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
        @if ($errors->has($name)) <small class="form-control-feedback">{{ $errors->first($name) }}</small> @endif
    </div>
</div> --}}
<div class="form-check">
    <input class="" type="checkbox" value="" id="{{ $name }}" name="{{ $name }}">
    <label class="" for="{{ $name }}">{{ $label }}</label>
    @if ($errors->has($name)) <small class="form-control-feedback">{{ $errors->first($name) }}</small> @endif
</div>

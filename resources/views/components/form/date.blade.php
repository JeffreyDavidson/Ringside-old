<div class="form-group @if ($errors->has($name)) {{ 'has-danger' }} @endif">
    <label class="control-label" for="{{ $name }}">{{ $label }}</label>
    <div class="input-group">
        <span class="input-group-addon">
            <i class="icon wb-calendar" aria-hidden="true"></i>
        </span>
        <input type="text" class="form-control datepicker" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"/>
    </div>
    @if ($errors->has($name)) <small class="form-control-feedback">{{ $errors->first($name) }}</small> @endif
</div>
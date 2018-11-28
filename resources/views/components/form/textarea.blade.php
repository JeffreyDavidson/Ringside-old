<div class="form-group @if ($errors->has($name)) {{ 'has-danger' }} @endif">
    @if ($label)
        <label class="form-control-label" for="{{ $name }}">{{ $label }}</label>
    @else
        <label class="form-control-label" for="{{ $name }}">&nbsp;</label>
    @endif

    @if ($addon)
        <div class="input-group">
            <textarea class="form-control" id="{{ $name }}" name="{{ $name }}" />{{ $value }}</textarea>
            <span class="input-group-addon">{{ $addonLabel }}</span>
        </div>
    @else
        <textarea class="form-control" id="{{ $name }}" name="{{ $name }}" />{{ $value }}</textarea>
    @endif

    @if ($instructions)
      <p><small>{{ $instructions }}</small></p>
    @endif

    @if ($errors->has($name)) <small class="form-control-feedback">{{ $errors->first($name) }}</small> @endif
</div>
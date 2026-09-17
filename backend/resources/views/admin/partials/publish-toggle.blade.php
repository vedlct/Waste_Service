@php
    $name = $name ?? 'is_active';
    $id = $id ?? str_replace(['[', ']'], '_', $name);
    $label = $label ?? 'Published';
    $help = $help ?? null;
    $checked = old($name, $checked ?? false);
@endphp

<div class="form-check form-switch">
    <input type="hidden" name="{{ $name }}" value="0">
    <input class="form-check-input @error($name) is-invalid @enderror" type="checkbox" role="switch" id="{{ $id }}" name="{{ $name }}" value="1" @checked((bool) $checked)>
    <label class="form-check-label fw-bold" for="{{ $id }}">{{ $label }}</label>
    @if ($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

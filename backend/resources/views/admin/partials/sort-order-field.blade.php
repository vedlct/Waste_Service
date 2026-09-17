@php
    $name = $name ?? 'sort_order';
    $id = $id ?? str_replace(['[', ']'], '_', $name);
    $label = $label ?? 'Sort order';
    $value = old($name, $value ?? 0);
    $help = $help ?? 'Lower numbers appear first.';
@endphp

<div>
    <label class="form-label fw-bold" for="{{ $id }}">{{ $label }}</label>
    <input id="{{ $id }}" type="number" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" value="{{ $value }}" min="0" step="1">
    @if ($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

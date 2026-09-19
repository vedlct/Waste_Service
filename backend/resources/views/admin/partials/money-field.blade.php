@php
    $name = $name ?? 'price';
    $id = $id ?? str_replace(['[', ']'], '_', $name);
    $label = $label ?? 'Price';
    $help = $help ?? 'Entered in pounds and stored in pence.';
    $required = $required ?? false;
    $value = old($name, \App\Support\Money::toPounds($pence ?? null));
@endphp

<div>
    <label class="form-label fw-bold" for="{{ $id }}">{{ $label }}</label>
    <div class="input-group">
        <span class="input-group-text">£</span>
        <input
            id="{{ $id }}"
            type="number"
            step="0.01"
            min="0"
            name="{{ $name }}"
            class="form-control @error($name) is-invalid @enderror"
            value="{{ $value }}"
            @required($required)
        >
    </div>
    @if ($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

@php
    $name = $name ?? 'pricing_status';
    $id = $id ?? str_replace(['[', ']'], '_', $name);
    $label = $label ?? 'Pricing status';
    $statuses = config('pricing.statuses', []);
    $allowed = $allowed ?? array_keys($statuses);
    $current = old($name, $value ?? null);
@endphp

<div>
    <label class="form-label fw-bold" for="{{ $id }}">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $name }}" class="form-select @error($name) is-invalid @enderror" required>
        @foreach ($allowed as $status)
            <option value="{{ $status }}" @selected($current === $status)>{{ $statuses[$status]['label'] ?? \Illuminate\Support\Str::headline($status) }}</option>
        @endforeach
    </select>
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

    <ul class="list-unstyled small text-muted mt-2 mb-0">
        @foreach ($allowed as $status)
            @if (! empty($statuses[$status]['description']))
                <li class="d-flex gap-2 mb-1">
                    <span class="fw-bold text-nowrap">{{ $statuses[$status]['label'] }}:</span>
                    <span>{{ $statuses[$status]['description'] }}</span>
                </li>
            @endif
        @endforeach
    </ul>
</div>

@php
    $config = config('pricing.statuses.'.$status, []);
    $label = $config['label'] ?? \Illuminate\Support\Str::headline($status);
    $badge = $config['badge'] ?? 'muted';
@endphp

<span class="status-pill {{ $badge }}">
    @if (! empty($config['warning']))
        <i class="bi bi-exclamation-triangle-fill"></i>
    @endif
    {{ $label }}
</span>

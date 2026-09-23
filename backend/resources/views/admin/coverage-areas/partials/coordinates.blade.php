@if ($area->has_coordinates)
    <a class="small text-decoration-none" href="{{ $area->map_url }}" target="_blank" rel="noopener">
        <i class="bi bi-pin-map-fill me-1"></i>{{ $area->latitude }}, {{ $area->longitude }}
    </a>
@else
    <span class="text-muted small">Not set</span>
@endif

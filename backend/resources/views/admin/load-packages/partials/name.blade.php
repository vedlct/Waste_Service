<div>
    <div class="fw-bold">
        {{ $package->name }}
        @if ($package->is_popular)
            <i class="bi bi-star-fill text-warning ms-1" title="Popular"></i>
        @endif
    </div>
    <div class="small text-muted">{{ $package->slug }}</div>
</div>

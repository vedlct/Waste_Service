<div>
    <div class="fw-bold">{{ $region->name }}</div>
    <div class="small text-muted">{{ $region->slug }}</div>
    @if ($region->description)
        <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($region->description, 90) }}</div>
    @endif
</div>

<div>
    <div class="fw-bold">{{ $charge->name }}</div>
    <div class="small text-muted">{{ $charge->slug }}</div>
    @if ($charge->description)
        <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($charge->description, 90) }}</div>
    @endif
</div>

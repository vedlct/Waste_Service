<div>
    <div class="fw-bold">{{ $page->title }}</div>
    <div class="small text-muted">{{ $page->route_path }}</div>
    @if ($page->service)
        <div class="small text-primary">Service page: {{ $page->service->name }}</div>
    @endif
</div>

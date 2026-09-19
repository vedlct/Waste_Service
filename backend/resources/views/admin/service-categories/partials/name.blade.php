<div>
    <div class="fw-bold">
        @if ($category->icon)
            <i class="bi bi-{{ $category->icon }} me-1 text-primary"></i>
        @endif
        {{ $category->name }}
    </div>
    <div class="small text-muted">{{ $category->slug }}</div>
</div>

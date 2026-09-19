<div>
    <div class="fw-bold">{{ $category->name }}</div>
    @if ($category->eyebrow)
        <div class="small text-primary fw-bold">{{ $category->eyebrow }}</div>
    @endif
    <div class="small text-muted">{{ $category->slug }}</div>
</div>

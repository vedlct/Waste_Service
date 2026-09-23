@php($placeholder = blank($page->meta_description) || $page->meta_description === \App\Http\Controllers\Admin\PageController::PLACEHOLDER_DESCRIPTION)
<div class="small">
    <div class="fw-bold text-break">{{ $page->meta_title ?: 'No meta title' }}</div>
    <div class="text-muted text-break">{{ \Illuminate\Support\Str::limit($page->meta_description ?: 'No meta description', 110) }}</div>
    @if ($placeholder && $page->is_indexable)
        <span class="status-pill role mt-1"><i class="bi bi-exclamation-triangle-fill"></i>Needs a real description</span>
    @endif
</div>

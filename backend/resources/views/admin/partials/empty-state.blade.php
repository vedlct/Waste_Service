<div class="text-center py-5">
    @isset($icon)
        <div class="metric-icon mx-auto mb-3"><i class="bi bi-{{ $icon }}"></i></div>
    @endisset
    <h3 class="h5 fw-black mb-2">{{ $title }}</h3>
    @isset($message)
        <p class="text-muted mb-0">{{ $message }}</p>
    @endisset
    @isset($actions)
        <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
            {{ $actions }}
        </div>
    @endisset
</div>

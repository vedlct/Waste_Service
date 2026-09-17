<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 {{ $class ?? 'mb-4' }}">
    <div>
        @isset($eyebrow)
            <p class="small fw-bold text-uppercase text-primary mb-1">{{ $eyebrow }}</p>
        @endisset
        <h2 class="h5 fw-black mb-0">{{ $title }}</h2>
        @isset($description)
            <p class="text-muted mb-0 mt-1">{{ $description }}</p>
        @endisset
    </div>

    @isset($actions)
        <div class="d-flex flex-wrap align-items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>

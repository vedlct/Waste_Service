<div>
    <div class="fw-bold">{{ $media->original_name ?: basename($media->path) }}</div>
    <a class="small text-muted text-decoration-none" href="{{ $media->url }}" target="_blank" rel="noopener">
        {{ $media->path }}
    </a>
    @if ($media->alt_text)
        <div class="small text-muted mt-1">{{ $media->alt_text }}</div>
    @endif
</div>

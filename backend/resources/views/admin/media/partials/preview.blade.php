<div class="media-thumb">
    @if ($media->is_image)
        <img src="{{ $media->url }}" alt="{{ $media->alt_text ?: $media->original_name ?: 'Media preview' }}">
    @else
        <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
    @endif
</div>

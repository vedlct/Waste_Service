@php
    $name = $name ?? 'image';
    $id = $id ?? str_replace(['[', ']'], '_', $name);
    $label = $label ?? 'Image';
    $help = $help ?? 'Upload a JPG, PNG, or WebP image.';
    $previewUrl = $previewUrl ?? null;
    $previewAlt = $previewAlt ?? $label;
@endphp

<div>
    <label class="form-label fw-bold" for="{{ $id }}">{{ $label }}</label>
    <div class="row g-3 align-items-center">
        <div class="col-sm-5 col-md-4">
            <div class="image-preview-frame">
                @if ($previewUrl)
                    <img src="{{ $previewUrl }}" alt="{{ $previewAlt }}">
                @else
                    <div class="text-center text-muted small px-3">
                        <i class="bi bi-image d-block fs-3 mb-1"></i>
                        No image selected
                    </div>
                @endif
            </div>
        </div>
        <div class="col-sm-7 col-md-8">
            <input id="{{ $id }}" type="file" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" accept="{{ $accept ?? 'image/jpeg,image/png,image/webp,application/pdf' }}">
            @if ($help)
                <div class="form-text">{{ $help }}</div>
            @endif
            @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

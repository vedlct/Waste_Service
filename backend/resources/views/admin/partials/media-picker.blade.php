@php
    $name = $name ?? 'media_id';
    $id = $id ?? str_replace(['[', ']'], '_', $name);
    $label = $label ?? 'Image';
    $help = $help ?? 'Pick an asset from the Media Library.';
    $selected = $selected ?? null;
    $currentId = old($name, $selected?->id);

    // On a validation redirect the old value may point at a different asset than the model holds.
    if ($currentId && (! $selected || (int) $selected->id !== (int) $currentId)) {
        $selected = \App\Models\MediaAsset::query()->find($currentId);
    }
@endphp

<div>
    <label class="form-label fw-bold" for="{{ $id }}">{{ $label }}</label>
    <div class="row g-3 align-items-center">
        <div class="col-sm-5 col-md-4">
            <div class="image-preview-frame" data-media-preview="{{ $id }}">
                @if ($selected && $selected->is_image)
                    <img src="{{ $selected->url }}" alt="{{ $selected->alt_text ?: $selected->original_name }}">
                @elseif ($selected)
                    <div class="text-center text-muted small px-3">
                        <i class="bi bi-file-earmark-text d-block fs-3 mb-1"></i>
                        {{ $selected->mime_type ?: 'File' }}
                    </div>
                @else
                    <div class="text-center text-muted small px-3">
                        <i class="bi bi-image d-block fs-3 mb-1"></i>
                        No media selected
                    </div>
                @endif
            </div>
        </div>
        <div class="col-sm-7 col-md-8">
            <select
                id="{{ $id }}"
                name="{{ $name }}"
                class="form-select media-picker @error($name) is-invalid @enderror"
                data-options-url="{{ route('admin.media.options') }}"
                data-preview-target="{{ $id }}"
                data-placeholder="Search the Media Library"
            >
                <option value=""></option>
                @if ($selected)
                    <option value="{{ $selected->id }}" selected>{{ $selected->original_name ?: basename($selected->path) }}</option>
                @endif
            </select>
            @if ($help)
                <div class="form-text">{{ $help }}</div>
            @endif
            @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            <a class="small text-decoration-none" href="{{ route('admin.media.create') }}" target="_blank" rel="noopener">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i>Upload new media
            </a>
        </div>
    </div>
</div>

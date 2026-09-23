@php($inUse = $media->isInUse())
<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ $media->url }}" target="_blank" rel="noopener" title="Open file"><i class="bi bi-box-arrow-up-right"></i></a>
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.media.edit', $media) }}" title="Edit details"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="{{ $inUse ? 'In use by other modules' : 'Delete media' }}"
        data-delete-action="{{ route('admin.media.destroy', $media) }}"
        data-delete-title="Delete {{ $media->original_name ?: basename($media->path) }}?"
        data-delete-message="This permanently removes the library record and the stored file."
        @disabled($inUse)
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>

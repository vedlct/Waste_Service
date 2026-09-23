@php($blocked = $region->areas_count > 0)
<div class="d-inline-flex gap-2">
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.coverage-areas.index', ['region' => $region->id]) }}" title="View areas"><i class="bi bi-geo-alt"></i></a>
    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.coverage-regions.edit', $region) }}" title="Edit region"><i class="bi bi-pencil-square"></i></a>
    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="{{ $blocked ? 'Move or delete its areas first' : 'Delete region' }}"
        data-delete-action="{{ route('admin.coverage-regions.destroy', $region) }}"
        data-delete-title="Delete {{ $region->name }}?"
        data-delete-message="This permanently removes the coverage region."
        @disabled($blocked)
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>

@php($publishedStatus = \App\Models\Review::publishedStatus())

<div class="d-inline-flex gap-2">
    @if ($review->status !== $publishedStatus)
        <form method="POST" action="{{ route('admin.reviews.moderate', $review) }}" class="d-inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $publishedStatus }}">
            <button class="btn btn-sm btn-outline-success" type="submit" title="Publish review"><i class="bi bi-check2"></i></button>
        </form>
    @endif

    @if ($review->status !== 'rejected')
        <form method="POST" action="{{ route('admin.reviews.moderate', $review) }}" class="d-inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button class="btn btn-sm btn-outline-secondary" type="submit" title="Reject review"><i class="bi bi-x-lg"></i></button>
        </form>
    @endif

    <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.reviews.edit', $review) }}" title="Edit review"><i class="bi bi-pencil-square"></i></a>

    <button
        class="btn btn-sm btn-outline-danger"
        type="button"
        title="Delete review"
        data-delete-action="{{ route('admin.reviews.destroy', $review) }}"
        data-delete-title="Delete the review by {{ $review->reviewer_name }}?"
        data-delete-message="This permanently removes the review."
    >
        <i class="bi bi-trash3"></i>
    </button>
</div>

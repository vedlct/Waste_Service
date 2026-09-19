<div>
    <div class="fw-bold">{{ $review->reviewer_name }}</div>
    @if ($review->reviewer_email)
        <div class="small text-muted">{{ $review->reviewer_email }}</div>
    @endif
</div>

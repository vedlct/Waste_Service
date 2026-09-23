<div>
    <div class="fw-bold">{{ $enquiry->service_label ?: 'General enquiry' }}</div>
    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($enquiry->message, 110) }}</div>
</div>

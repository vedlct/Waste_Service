<div>
    <div class="fw-bold">{{ $enquiry->name }}</div>
    <a class="small text-decoration-none d-block" href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
    <a class="small text-muted text-decoration-none" href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
</div>

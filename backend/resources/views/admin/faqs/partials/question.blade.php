<div>
    <div class="fw-bold">{{ $faq->question }}</div>
    <div class="small text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($faq->answer), 110) }}</div>
</div>

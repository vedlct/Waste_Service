@php($config = config('reviews.statuses.'.$review->status, []))
<span class="status-pill {{ $config['badge'] ?? 'muted' }}">{{ $config['label'] ?? \Illuminate\Support\Str::headline($review->status) }}</span>

@php($config = config('enquiries.statuses.'.$enquiry->status, []))
<span class="status-pill {{ $config['badge'] ?? 'muted' }}">{{ $config['label'] ?? \Illuminate\Support\Str::headline($enquiry->status) }}</span>

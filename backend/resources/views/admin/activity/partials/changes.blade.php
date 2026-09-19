@if (empty($log->changes))
    <span class="small text-muted">{{ $log->ip_address ? 'From '.$log->ip_address : '' }}</span>
@else
    <ul class="list-unstyled small mb-0">
        @foreach ($log->changes as $attribute => $change)
            <li class="text-break">
                <span class="fw-bold">{{ \Illuminate\Support\Str::headline($attribute) }}:</span>
                <span class="text-muted">{{ \Illuminate\Support\Str::limit(\App\Models\ActivityLog::displayValue($change['old'] ?? null), 60) }}</span>
                <i class="bi bi-arrow-right mx-1"></i>
                <span>{{ \Illuminate\Support\Str::limit(\App\Models\ActivityLog::displayValue($change['new'] ?? null), 60) }}</span>
            </li>
        @endforeach
    </ul>
@endif

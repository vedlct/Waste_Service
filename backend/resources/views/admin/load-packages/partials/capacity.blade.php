<div class="small">
    <div>{{ $package->max_weight_kg ? $package->max_weight_kg . ' kg' : 'Weight N/A' }}</div>
    <div class="text-muted">
        {{ $package->volume_cubic_yards ? $package->volume_cubic_yards . ' yd³' : 'Volume N/A' }}
        &middot; {{ $package->sack_equivalent ? $package->sack_equivalent . ' sacks' : 'Sacks N/A' }}
    </div>
    <div class="text-muted">{{ $package->loading_time_minutes ? $package->loading_time_minutes . ' min loading' : 'Loading time N/A' }}</div>
</div>

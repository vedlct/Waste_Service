@php
    $rating = (int) ($rating ?? 0);
    $max = (int) config('reviews.max_rating', 5);
@endphp

<span class="text-nowrap" title="{{ $rating }} out of {{ $max }}">
    @for ($star = 1; $star <= $max; $star++)
        <i class="bi bi-star{{ $star <= $rating ? '-fill text-warning' : ' text-muted' }}"></i>
    @endfor
</span>

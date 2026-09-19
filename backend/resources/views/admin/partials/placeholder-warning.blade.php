@php
    $count = $count ?? 0;
    $label = $label ?? 'records';
    $message = $message ?? 'Placeholder prices were carried over and must be confirmed with the business before launch.';
@endphp

@if ($count > 0)
    <div class="alert alert-warning border-0 rounded-4 d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
        <div>
            <div class="fw-black">{{ $count }} {{ $label }} still use placeholder prices</div>
            <div class="small mb-0">{{ $message }}</div>
        </div>
    </div>
@endif

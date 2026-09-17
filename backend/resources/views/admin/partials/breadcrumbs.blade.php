@php
    $items = $items ?? [];
@endphp

@if (count($items) > 0)
    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
        <ol class="breadcrumb mb-0">
            @foreach ($items as $item)
                @php
                    $label = $item['label'] ?? '';
                    $url = $item['url'] ?? null;
                    $isActive = $loop->last || blank($url);
                @endphp
                <li class="breadcrumb-item {{ $isActive ? 'active' : '' }}" @if($isActive) aria-current="page" @endif>
                    @if ($isActive)
                        {{ $label }}
                    @else
                        <a href="{{ $url }}">{{ $label }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif

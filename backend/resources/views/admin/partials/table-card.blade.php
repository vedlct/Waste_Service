<div class="page-panel {{ $class ?? 'p-4' }}">
    @isset($header)
        {{ $header }}
    @endisset

    <div class="table-responsive">
        {{ $slot }}
    </div>
</div>

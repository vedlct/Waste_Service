<div class="d-inline-flex flex-wrap gap-1">
    @include('admin.partials.status-badge', ['label' => $region->areas_count . ' areas', 'class' => $region->areas_count ? 'role' : 'muted'])
    @if ($region->featured_areas_count > 0)
        @include('admin.partials.status-badge', ['label' => $region->featured_areas_count . ' featured', 'class' => 'active'])
    @endif
</div>

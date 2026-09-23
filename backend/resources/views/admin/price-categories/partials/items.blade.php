<div class="d-inline-flex flex-wrap gap-1">
    @include('admin.partials.status-badge', ['label' => $category->items_count . ' items', 'class' => $category->items_count ? 'role' : 'muted'])
    @if ($category->placeholder_items_count > 0)
        @include('admin.partials.pricing-status-badge', ['status' => 'placeholder'])
    @endif
</div>

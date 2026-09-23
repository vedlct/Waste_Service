<div class="d-inline-flex flex-wrap gap-1">
    @include('admin.partials.status-badge', ['label' => $service->is_featured ? 'Featured' : 'Standard', 'class' => $service->is_featured ? 'role' : 'muted'])
    @include('admin.partials.status-badge', ['label' => $service->is_bookable ? 'Bookable' : 'Enquiry only', 'class' => $service->is_bookable ? 'active' : 'muted'])
    @include('admin.partials.status-badge', ['label' => $service->content_blocks_count . ' blocks', 'class' => 'muted'])
</div>

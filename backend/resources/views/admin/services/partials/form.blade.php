@php($mode = $mode ?? 'create')

<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Service details</h2>
        <p class="text-muted mb-4">The name, slug, and route path drive the public service page URL.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="name">Name</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $service->name) }}" required maxlength="255">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="short_name">Short name</label>
                <input id="short_name" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name', $service->short_name) }}" maxlength="255">
                <div class="form-text">Used where the full name is too long, such as navigation.</div>
                @error('short_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="slug">Slug</label>
                <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $service->slug) }}" maxlength="255">
                <div class="form-text">Leave blank to generate the slug from the name.</div>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="route_path">Route path</label>
                <input id="route_path" name="route_path" class="form-control @error('route_path') is-invalid @enderror" value="{{ old('route_path', $service->route_path) }}" maxlength="255" placeholder="/rubbish-removal">
                <div class="form-text">Must start with a slash. Leave blank to derive it from the slug.</div>
                @error('route_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="service_category_id">Category</label>
                <select id="service_category_id" name="service_category_id" class="form-select select2 @error('service_category_id') is-invalid @enderror">
                    <option value="">Uncategorised</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('service_category_id', $service->service_category_id) === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('service_category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="page_id">Linked page</label>
                <select id="page_id" name="page_id" class="form-select select2 @error('page_id') is-invalid @enderror">
                    <option value="">Not linked</option>
                    @foreach ($pages as $pageOption)
                        <option value="{{ $pageOption->id }}" @selected((int) old('page_id', $service->page_id) === $pageOption->id)>{{ $pageOption->title }} ({{ $pageOption->route_path }})</option>
                    @endforeach
                </select>
                <div class="form-text">SEO metadata below is stored on the linked page.</div>
                @error('page_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="border-top mt-4 pt-4">
            <p class="small fw-bold text-uppercase text-primary mb-1">Hero</p>
            <h3 class="h5 fw-black mb-3">Service hero</h3>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold" for="headline">Headline</label>
                    <input id="headline" name="headline" class="form-control @error('headline') is-invalid @enderror" value="{{ old('headline', $service->headline) }}" maxlength="255">
                    <div class="form-text">The main heading at the top of the service page.</div>
                    @error('headline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="summary">Summary</label>
                    <textarea id="summary" name="summary" rows="3" class="form-control @error('summary') is-invalid @enderror" maxlength="2000">{{ old('summary', $service->summary) }}</textarea>
                    <div class="form-text">The paragraph under the page heading, also used on related service cards.</div>
                    @error('summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    @include('admin.partials.media-picker', [
                        'name' => 'hero_media_id',
                        'label' => 'Hero image',
                        'selected' => $service->heroMedia,
                        'help' => 'Shown at the top of the service page.',
                    ])
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="description">Description</label>
                    <textarea id="description" name="description" rows="8" class="form-control @error('description') is-invalid @enderror" maxlength="20000">{{ old('description', $service->description) }}</textarea>
                    <div class="form-text">Kept for listings and future layouts; the current page design does not show it. Add content blocks to put new sections on the page.</div>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        @include('admin.partials.seo-fields', [
            'model' => $page,
            'titleName' => 'meta_title',
            'descriptionName' => 'meta_description',
            'showKeywords' => false,
            'heading' => 'Search metadata',
            'description' => $service->page_id
                ? 'Saved on the linked page record.'
                : 'Link a page above before editing SEO metadata. Values entered here are ignored until a page is linked.',
        ])
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4">
            <div class="metric-icon mb-3"><i class="bi bi-broadcast"></i></div>
            <h3 class="h5 fw-black">Publishing</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    <label class="form-label fw-bold" for="status">Status</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach (\App\Models\Service::STATUSES as $status)
                            <option value="{{ $status }}" @selected(old('status', $service->status) === $status)>{{ str($status)->headline() }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Publishing stamps the publish date. Moving back to draft clears it.</div>
                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $service->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_featured',
                        'label' => 'Featured service',
                        'checked' => (bool) ($service->is_featured ?? false),
                        'help' => 'Featured services appear in the highlighted lists.',
                    ])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_bookable',
                        'label' => 'Bookable',
                        'checked' => (bool) ($service->is_bookable ?? true),
                        'help' => 'Turn off for services that only take enquiries.',
                    ])
                </div>
                @if ($service->exists)
                    <div class="col-12">
                        <dl class="row small mb-0">
                            <dt class="col-6 text-muted fw-normal">Published</dt>
                            <dd class="col-6">{{ $service->published_at?->format('d M Y, h:i A') ?? 'Not published' }}</dd>
                            <dt class="col-6 text-muted fw-normal">Last updated</dt>
                            <dd class="col-6 mb-0">{{ $service->updated_at?->format('d M Y, h:i A') ?? 'Never' }}</dd>
                        </dl>
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-4 border bg-light p-4 mt-3">
            <div class="metric-icon mb-3"><i class="bi bi-link-45deg"></i></div>
            <h3 class="h5 fw-black">Related services</h3>
            <p class="text-muted small">Shown as cross-links at the bottom of the service page.</p>

            <select name="related_service_ids[]" class="form-select select2 @error('related_service_ids') is-invalid @enderror" multiple data-placeholder="Pick related services">
                @foreach ($availableServices as $option)
                    <option value="{{ $option->id }}" @selected(in_array($option->id, old('related_service_ids', $selectedRelatedIds) ?: [], false))>
                        {{ $option->name }} ({{ str($option->status)->headline() }})
                    </option>
                @endforeach
            </select>
            @error('related_service_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('related_service_ids.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

            @if (! $service->exists)
                <p class="small text-muted mb-0 mt-3">Content blocks can be added after the service is saved.</p>
            @endif
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.services.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit">
        <i class="bi bi-check2 me-1"></i>
        {{ $mode === 'create' ? 'Create Service' : 'Save Service' }}
    </button>
</div>

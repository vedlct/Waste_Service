<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Category details</h2>
        <p class="text-muted mb-4">Price categories drive the item picker on the prices and checkout pages.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="name">Name</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required maxlength="255">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="slug">Slug</label>
                <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}" maxlength="255">
                <div class="form-text">Leave blank to generate the slug from the name.</div>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="eyebrow">Eyebrow</label>
                <input id="eyebrow" name="eyebrow" class="form-control @error('eyebrow') is-invalid @enderror" value="{{ old('eyebrow', $category->eyebrow) }}" maxlength="255">
                <div class="form-text">Small label shown above the category name on the website.</div>
                @error('eyebrow')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="description">Description</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" maxlength="2000">{{ old('description', $category->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                @include('admin.partials.media-picker', [
                    'name' => 'image_id',
                    'label' => 'Category image',
                    'selected' => $category->image,
                    'help' => 'Shown on the category card in the item picker.',
                ])
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4 h-100">
            <div class="metric-icon mb-3"><i class="bi bi-tags-fill"></i></div>
            <h3 class="h5 fw-black">Display</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $category->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_active',
                        'label' => 'Active',
                        'checked' => (bool) ($category->is_active ?? true),
                        'help' => 'Inactive categories stay hidden on the website.',
                    ])
                </div>
                <div class="col-12">
                    <p class="small text-muted mb-0">A category cannot be deleted while it still has service items, because deleting it would remove their prices.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.price-categories.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Category</button>
</div>

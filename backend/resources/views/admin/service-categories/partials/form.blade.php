<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Category details</h2>
        <p class="text-muted mb-4">Categories group services on the website and in the admin catalogue.</p>

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
            <div class="col-md-6">
                <label class="form-label fw-bold" for="parent_id">Parent category</label>
                <select id="parent_id" name="parent_id" class="form-select select2 @error('parent_id') is-invalid @enderror">
                    <option value="">Top level</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" @selected((int) old('parent_id', $category->parent_id) === $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="icon">Bootstrap icon</label>
                <input id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $category->icon) }}" maxlength="255" placeholder="trash3">
                <div class="form-text">Bootstrap Icons name without the <code>bi-</code> prefix.</div>
                @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="description">Description</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" maxlength="2000">{{ old('description', $category->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                @include('admin.partials.sort-order-field', ['value' => $category->sort_order ?? 0])
            </div>
            <div class="col-md-6 d-flex align-items-end">
                @include('admin.partials.publish-toggle', [
                    'name' => 'is_active',
                    'label' => 'Active',
                    'checked' => (bool) ($category->is_active ?? true),
                    'help' => 'Inactive categories stay hidden on the website.',
                ])
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4 h-100">
            <div class="metric-icon mb-3"><i class="bi bi-diagram-3-fill"></i></div>
            <h3 class="h5 fw-black">Category guidance</h3>
            <p class="text-muted">Keep the list shallow. One level of sub categories is usually enough for the website navigation.</p>
            <p class="small text-muted mb-0">A category cannot be deleted while services or sub categories still point at it.</p>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.service-categories.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Category</button>
</div>

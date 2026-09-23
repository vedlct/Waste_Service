<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Item details</h2>
        <p class="text-muted mb-4">Service items are the individual priced lines customers add to a booking.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="name">Name</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}" required maxlength="255">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="price_category_id">Price category</label>
                <select id="price_category_id" name="price_category_id" class="form-select select2 @error('price_category_id') is-invalid @enderror" required>
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('price_category_id', $item->price_category_id) === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('price_category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="slug">Slug</label>
                <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $item->slug) }}" maxlength="255">
                <div class="form-text">Leave blank to generate the slug from the name.</div>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="sku">SKU</label>
                <input id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $item->sku) }}" maxlength="255">
                <div class="form-text">Leave blank to generate the SKU from the name.</div>
                @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="description">Description</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" maxlength="2000">{{ old('description', $item->description) }}</textarea>
                <div class="form-text">Shown under the item name so customers pick the right line.</div>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                @include('admin.partials.media-picker', [
                    'name' => 'image_id',
                    'label' => 'Item image',
                    'selected' => $item->image,
                    'help' => 'Optional thumbnail shown in the item picker.',
                ])
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4">
            <div class="metric-icon mb-3"><i class="bi bi-currency-pound"></i></div>
            <h3 class="h5 fw-black">Pricing</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.money-field', [
                        'name' => 'price',
                        'label' => 'Price including VAT',
                        'pence' => $item->price_pence,
                        'required' => true,
                        'help' => 'Entered in pounds and stored in pence. Set to 0 for quote required items.',
                    ])
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="vat_rate_percent">VAT rate</label>
                    <div class="input-group">
                        <input id="vat_rate_percent" type="number" step="0.01" min="0" max="100" name="vat_rate_percent" class="form-control @error('vat_rate_percent') is-invalid @enderror" value="{{ old('vat_rate_percent', $vatPercent) }}" required>
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">Stored as basis points, so 20% is saved as 2000.</div>
                    @error('vat_rate_percent')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    @include('admin.partials.pricing-status-field', ['value' => $item->pricing_status])
                </div>
                @if ($item->exists && $item->needsPriceReview())
                    <div class="col-12">
                        @include('admin.partials.placeholder-warning', [
                            'count' => 1,
                            'label' => 'item',
                            'message' => 'Confirm this price with the business, then move the status to Confirmed.',
                        ])
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-4 border bg-light p-4 mt-3">
            <div class="metric-icon mb-3"><i class="bi bi-toggles"></i></div>
            <h3 class="h5 fw-black">Display</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $item->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_active',
                        'label' => 'Active',
                        'checked' => (bool) ($item->is_active ?? true),
                        'help' => 'Inactive items stay hidden in the booking picker.',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.service-items.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Item</button>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Area details</h2>
        <p class="text-muted mb-4">Areas are the town and borough names listed on the Areas We Cover page.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="name">Name</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $area->name) }}" required maxlength="255">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="slug">Slug</label>
                <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $area->slug) }}" maxlength="255">
                <div class="form-text">Leave blank to generate the slug from the name.</div>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="coverage_region_id">Region</label>
                <select id="coverage_region_id" name="coverage_region_id" class="form-select select2 @error('coverage_region_id') is-invalid @enderror" required>
                    <option value="">Select a region</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" @selected((int) old('coverage_region_id', $area->coverage_region_id) === $region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
                @error('coverage_region_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="postcode_prefix">Postcode prefix</label>
                <input id="postcode_prefix" name="postcode_prefix" class="form-control text-uppercase @error('postcode_prefix') is-invalid @enderror" value="{{ old('postcode_prefix', $area->postcode_prefix) }}" maxlength="8" placeholder="PO1">
                <div class="form-text">The outward part of a UK postcode, such as PO1, SW1A, or E14.</div>
                @error('postcode_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="border-top mt-4 pt-4">
            <p class="small fw-bold text-uppercase text-primary mb-1">Map</p>
            <h3 class="h5 fw-black mb-3">Coordinates</h3>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="latitude">Latitude</label>
                    <input id="latitude" type="number" step="0.0000001" min="-90" max="90" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $area->latitude) }}">
                    @error('latitude')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="longitude">Longitude</label>
                    <input id="longitude" type="number" step="0.0000001" min="-180" max="180" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $area->longitude) }}">
                    @error('longitude')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <div class="form-text">Set both or leave both blank. Used by the map on the Areas We Cover page.</div>
                    @if ($area->exists && $area->has_coordinates)
                        <a class="small text-decoration-none" href="{{ $area->map_url }}" target="_blank" rel="noopener">
                            <i class="bi bi-pin-map-fill me-1"></i>Check this location on the map
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4 h-100">
            <div class="metric-icon mb-3"><i class="bi bi-geo-alt-fill"></i></div>
            <h3 class="h5 fw-black">Display</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $area->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_featured',
                        'label' => 'Featured area',
                        'checked' => (bool) ($area->is_featured ?? false),
                        'help' => 'Featured areas are highlighted at the top of the region.',
                    ])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_active',
                        'label' => 'Active',
                        'checked' => (bool) ($area->is_active ?? true),
                        'help' => 'Inactive areas stay hidden on the website.',
                    ])
                </div>
                <div class="col-12">
                    <p class="small text-muted mb-0">Deleting a region deletes its areas, so a region with areas cannot be removed.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.coverage-areas.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Area</button>
</div>

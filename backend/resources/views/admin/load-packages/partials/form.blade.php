<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Package details</h2>
        <p class="text-muted mb-4">Load packages are the fixed-size man and van options customers book by volume.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="name">Name</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required maxlength="255">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="slug">Slug</label>
                <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $package->slug) }}" maxlength="255">
                <div class="form-text">Leave blank to generate the slug from the name.</div>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="border-top mt-4 pt-4">
            <p class="small fw-bold text-uppercase text-primary mb-1">Capacity</p>
            <h3 class="h5 fw-black mb-3">What the package covers</h3>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="max_weight_kg">Max weight (kg)</label>
                    <input id="max_weight_kg" type="number" step="1" min="0" name="max_weight_kg" class="form-control @error('max_weight_kg') is-invalid @enderror" value="{{ old('max_weight_kg', $package->max_weight_kg) }}">
                    @error('max_weight_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="volume_cubic_yards">Volume (cubic yards)</label>
                    <input id="volume_cubic_yards" type="number" step="0.01" min="0" name="volume_cubic_yards" class="form-control @error('volume_cubic_yards') is-invalid @enderror" value="{{ old('volume_cubic_yards', $package->volume_cubic_yards) }}">
                    @error('volume_cubic_yards')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="sack_equivalent">Sack equivalent</label>
                    <input id="sack_equivalent" type="number" step="1" min="0" name="sack_equivalent" class="form-control @error('sack_equivalent') is-invalid @enderror" value="{{ old('sack_equivalent', $package->sack_equivalent) }}">
                    <div class="form-text">Roughly how many builders sacks the package holds.</div>
                    @error('sack_equivalent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="loading_time_minutes">Loading time (minutes)</label>
                    <input id="loading_time_minutes" type="number" step="1" min="0" name="loading_time_minutes" class="form-control @error('loading_time_minutes') is-invalid @enderror" value="{{ old('loading_time_minutes', $package->loading_time_minutes) }}">
                    @error('loading_time_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
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
                        'name' => 'price_inc_vat',
                        'label' => 'Price including VAT',
                        'pence' => $package->price_inc_vat_pence,
                        'required' => true,
                    ])
                </div>
                <div class="col-12">
                    @include('admin.partials.money-field', [
                        'name' => 'price_ex_vat',
                        'label' => 'Price excluding VAT',
                        'pence' => $package->price_ex_vat_pence,
                        'help' => 'Leave blank to calculate it from the inc-VAT price and the VAT rate.',
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
                    @include('admin.partials.pricing-status-field', ['value' => $package->pricing_status])
                </div>
                @if ($package->exists && $package->needsPriceReview())
                    <div class="col-12">
                        @include('admin.partials.placeholder-warning', [
                            'count' => 1,
                            'label' => 'package',
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
                    @include('admin.partials.sort-order-field', ['value' => $package->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_popular',
                        'label' => 'Most popular',
                        'checked' => (bool) ($package->is_popular ?? false),
                        'help' => 'Highlights the package on the booking page.',
                    ])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_active',
                        'label' => 'Active',
                        'checked' => (bool) ($package->is_active ?? true),
                        'help' => 'Inactive packages stay hidden on the website.',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.load-packages.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Package</button>
</div>

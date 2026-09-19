<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Charge details</h2>
        <p class="text-muted mb-4">Extra charges are surcharges and fees applied on top of the booking total.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="name">Name</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $charge->name) }}" required maxlength="255">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="slug">Slug</label>
                <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $charge->slug) }}" maxlength="255">
                <div class="form-text">Leave blank to generate the slug from the name.</div>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="description">Description</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" maxlength="2000">{{ old('description', $charge->description) }}</textarea>
                <div class="form-text">Explains to the customer when the charge applies.</div>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="charge_type">Charge type</label>
                <select id="charge_type" name="charge_type" class="form-select select2 @error('charge_type') is-invalid @enderror" required>
                    @foreach (config('pricing.charge_types') as $value => $label)
                        <option value="{{ $value }}" @selected(old('charge_type', $charge->charge_type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('charge_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 d-flex align-items-end">
                @include('admin.partials.publish-toggle', [
                    'name' => 'is_variable',
                    'label' => 'Variable / quoted per job',
                    'checked' => (bool) ($charge->is_variable ?? false),
                    'help' => 'Variable charges have no fixed amount; the customer is quoted first.',
                ])
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4">
            <div class="metric-icon mb-3"><i class="bi bi-currency-pound"></i></div>
            <h3 class="h5 fw-black">Amount</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.money-field', [
                        'name' => 'amount',
                        'label' => 'Charge amount',
                        'pence' => $charge->amount_pence,
                        'help' => 'Required for fixed charges. Leave blank when the charge is variable.',
                    ])
                </div>
                <div class="col-12">
                    @include('admin.partials.pricing-status-field', ['value' => $charge->pricing_status])
                </div>
            </div>
        </div>

        <div class="rounded-4 border bg-light p-4 mt-3">
            <div class="metric-icon mb-3"><i class="bi bi-toggles"></i></div>
            <h3 class="h5 fw-black">Display</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $charge->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_active',
                        'label' => 'Active',
                        'checked' => (bool) ($charge->is_active ?? true),
                        'help' => 'Inactive charges are never applied at checkout.',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.extra-charges.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Charge</button>
</div>

@php
    $components = config('service_cms.block_components', []);
    $currentComponent = old('component', $block->component);

    // Keep a legacy component value selectable so editing an older block never silently rewrites it.
    if ($currentComponent && ! array_key_exists($currentComponent, $components)) {
        $components = [$currentComponent => \Illuminate\Support\Str::headline($currentComponent)] + $components;
    }
@endphp

<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Block details</h2>
        <p class="text-muted mb-4">The component decides how the frontend renders this section. Blocks appear below the page's built-in sections, just above the quote form. A <em>Service overview</em> block is not shown, because the page design already covers it. For a <em>Call to action</em>, the first item's title and link set the button.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="heading">Heading</label>
                <input id="heading" name="heading" class="form-control @error('heading') is-invalid @enderror" value="{{ old('heading', $block->heading) }}" maxlength="255">
                @error('heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="block_key">Block key</label>
                <input id="block_key" name="block_key" class="form-control @error('block_key') is-invalid @enderror" value="{{ old('block_key', $block->block_key) }}" maxlength="120" placeholder="overview">
                <div class="form-text">Unique per service. Leave blank to derive it from the heading.</div>
                @error('block_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="component">Component</label>
                <select id="component" name="component" class="form-select select2 @error('component') is-invalid @enderror" required>
                    @foreach ($components as $value => $label)
                        <option value="{{ $value }}" @selected($currentComponent === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('component')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="eyebrow">Eyebrow</label>
                <input id="eyebrow" name="eyebrow" class="form-control @error('eyebrow') is-invalid @enderror" value="{{ old('eyebrow', $block->eyebrow) }}" maxlength="255">
                <div class="form-text">Small label shown above the heading.</div>
                @error('eyebrow')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="subheading">Subheading</label>
                <input id="subheading" name="subheading" class="form-control @error('subheading') is-invalid @enderror" value="{{ old('subheading', $block->subheading) }}" maxlength="255">
                @error('subheading')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="body">Body</label>
                <textarea id="body" name="body" rows="6" class="form-control @error('body') is-invalid @enderror" maxlength="20000">{{ old('body', $block->body) }}</textarea>
                @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                @include('admin.partials.media-picker', [
                    'name' => 'media_id',
                    'label' => 'Block image',
                    'selected' => $block->media,
                    'help' => 'Optional image rendered with this section.',
                ])
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4">
            <div class="metric-icon mb-3"><i class="bi bi-toggles"></i></div>
            <h3 class="h5 fw-black">Display</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $block->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_enabled',
                        'label' => 'Enabled',
                        'checked' => (bool) ($block->is_enabled ?? true),
                        'help' => 'Disabled blocks stay hidden on the website.',
                    ])
                </div>
            </div>
        </div>

        <div class="rounded-4 border bg-light p-4 mt-3">
            <div class="metric-icon mb-3"><i class="bi bi-list-ul"></i></div>
            <h3 class="h5 fw-black">Block items</h3>
            <p class="text-muted small mb-0">
                @if ($block->exists)
                    Items are managed from the block edit screen below the form.
                @else
                    Save the block first, then add its items.
                @endif
            </p>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.services.blocks.index', $service) }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Block</button>
</div>

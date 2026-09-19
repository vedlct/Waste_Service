<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Item details</h2>
        <p class="text-muted mb-4">Items belong to <strong>{{ $block->heading ?: $block->block_key }}</strong> on {{ $service->name }}.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="title">Title</label>
                <input id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $item->title) }}" required maxlength="255">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="subtitle">Subtitle</label>
                <input id="subtitle" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $item->subtitle) }}" maxlength="255">
                @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="body">Body</label>
                <textarea id="body" name="body" rows="4" class="form-control @error('body') is-invalid @enderror" maxlength="5000">{{ old('body', $item->body) }}</textarea>
                @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="icon">Bootstrap icon</label>
                <input id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $item->icon) }}" maxlength="255" placeholder="check2-circle">
                <div class="form-text">Bootstrap Icons name without the <code>bi-</code> prefix.</div>
                @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="url">Link URL</label>
                <input id="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $item->url) }}" maxlength="255" placeholder="/prices">
                <div class="form-text">Optional. Accepts a site path or a full URL.</div>
                @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                @include('admin.partials.media-picker', [
                    'name' => 'media_id',
                    'label' => 'Item image',
                    'selected' => $item->media,
                    'help' => 'Optional image for card style blocks.',
                ])
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4 h-100">
            <div class="metric-icon mb-3"><i class="bi bi-toggles"></i></div>
            <h3 class="h5 fw-black">Display</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $item->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_enabled',
                        'label' => 'Enabled',
                        'checked' => (bool) ($item->is_enabled ?? true),
                        'help' => 'Disabled items stay hidden on the website.',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.services.blocks.edit', [$service, $block]) }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Item</button>
</div>

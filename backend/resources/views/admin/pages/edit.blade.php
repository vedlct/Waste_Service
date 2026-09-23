@extends('layouts.app')

@section('title', 'Edit Page')
@section('eyebrow', 'Content')
@section('page-title', 'Edit Page')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Pages & SEO', 'url' => route('admin.pages.index')],
    ['label' => $page->title],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-7">
                <h2 class="h5 fw-black mb-1">Page details</h2>
                <p class="text-muted mb-4">Served at <code>{{ $page->route_path }}</code>.</p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="title">Title</label>
                        <input id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $page->title) }}" required maxlength="255">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="navigation_label">Navigation label</label>
                        <input id="navigation_label" name="navigation_label" class="form-control @error('navigation_label') is-invalid @enderror" value="{{ old('navigation_label', $page->navigation_label) }}" maxlength="255">
                        @error('navigation_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="border-top mt-4 pt-4">
                    <p class="small fw-bold text-uppercase text-primary mb-1">SEO</p>
                    <h3 class="h5 fw-black mb-1">Search result</h3>
                    <p class="text-muted mb-3">How this page appears in Google and when a link is shared.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="meta_title">Meta title</label>
                            <input id="meta_title" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $page->meta_title) }}" maxlength="255" data-length-counter="meta_title_count" data-length-target="60">
                            <div class="form-text"><span id="meta_title_count"></span> Search engines show about 60 characters.</div>
                            @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold" for="meta_description">Meta description</label>
                            <textarea id="meta_description" name="meta_description" rows="3" class="form-control @error('meta_description') is-invalid @enderror" maxlength="1000" data-length-counter="meta_description_count" data-length-target="155">{{ old('meta_description', $page->meta_description) }}</textarea>
                            <div class="form-text"><span id="meta_description_count"></span> Search engines show about 155 characters.</div>
                            @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <p class="form-label fw-bold mb-2">Preview</p>
                            <div class="rounded-4 border bg-white p-3" aria-live="polite">
                                <div class="small text-success text-truncate">{{ $siteUrl }}{{ $page->route_path === '/' ? '' : $page->route_path }}</div>
                                <div class="fs-5 text-primary text-truncate" data-preview="title"></div>
                                <div class="small text-muted" data-preview="description"></div>
                            </div>
                            <div class="form-text">Roughly how the page appears in Google. The search engine may still rewrite it.</div>
                        </div>
                    </div>
                </div>

                <div class="border-top mt-4 pt-4">
                    <h3 class="h5 fw-black mb-1">Social sharing</h3>
                    <p class="text-muted mb-3">The card shown when the page is shared on Facebook, WhatsApp, X or LinkedIn. Leave blank to reuse the meta title and description.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="og_title">Share title</label>
                            <input id="og_title" name="og_title" class="form-control @error('og_title') is-invalid @enderror" value="{{ old('og_title', $page->og_title) }}" maxlength="255" data-length-counter="og_title_count" data-length-target="60">
                            <div class="form-text"><span id="og_title_count"></span></div>
                            @error('og_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold" for="og_description">Share description</label>
                            <textarea id="og_description" name="og_description" rows="2" class="form-control @error('og_description') is-invalid @enderror" maxlength="1000" data-length-counter="og_description_count" data-length-target="200">{{ old('og_description', $page->og_description) }}</textarea>
                            <div class="form-text"><span id="og_description_count"></span></div>
                            @error('og_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            @include('admin.partials.media-picker', [
                                'name' => 'og_image_id',
                                'label' => 'Share image',
                                'selected' => $page->ogImage,
                                'help' => 'Best at 1200 × 630 pixels. Leave empty to use the default share image from Site Settings.',
                            ])
                        </div>
                    </div>
                </div>

                <div class="border-top mt-4 pt-4">
                    <h3 class="h5 fw-black mb-1">Canonical URL</h3>
                    <p class="text-muted mb-3">Only needed when this page duplicates another. Search engines are told to credit that address instead.</p>

                    <label class="form-label fw-bold" for="canonical_url">Canonical URL</label>
                    <input id="canonical_url" name="canonical_url" class="form-control @error('canonical_url') is-invalid @enderror" value="{{ old('canonical_url', $page->canonical_url) }}" maxlength="2048" placeholder="{{ $page->route_path }}">
                    <div class="form-text">Leave blank to use this page's own address. Accepts a path such as <code>/houseClearance</code> or a full https address.</div>
                    @error('canonical_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-lg-5">
                <div class="rounded-4 border bg-light p-4 h-100">
                    <div class="metric-icon mb-3"><i class="bi bi-search"></i></div>
                    <h3 class="h5 fw-black">Indexing</h3>

                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_indexable',
                        'label' => 'Show in search results',
                        'checked' => (bool) $page->is_indexable,
                        'help' => 'Off adds a noindex tag and leaves the page out of the sitemap.',
                    ])

                    <div class="mt-3">
                        @include('admin.partials.publish-toggle', [
                            'name' => 'is_followable',
                            'label' => 'Let search engines follow links',
                            'checked' => (bool) $page->is_followable,
                            'help' => 'Off adds a nofollow tag, so links on this page pass no ranking to the pages they point at.',
                        ])
                    </div>

                    @if ($page->service)
                        <p class="small text-muted mt-3 mb-0">
                            This is the page for the service
                            <a href="{{ route('admin.services.edit', $page->service) }}">{{ $page->service->name }}</a>.
                            Its meta title and description can be edited here or on the service form; both save to this page.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
            <a class="btn btn-outline-tee" href="{{ route('admin.pages.index') }}">Cancel</a>
            <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Page</button>
        </div>
    </form>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    document.querySelectorAll('[data-length-counter]').forEach(function (field) {
        const counter = document.getElementById(field.dataset.lengthCounter);
        const target = Number(field.dataset.lengthTarget);

        const update = function () {
            const length = field.value.length;
            counter.textContent = length + ' / ' + target + ' characters.';
            counter.className = length > target ? 'text-warning fw-bold' : '';
        };

        field.addEventListener('input', update);
        update();
    });

    (function () {
        const fallbackTitle = @json($page->title);
        const fallbackDescription = @json($defaultDescription);
        const titleField = document.getElementById('meta_title');
        const descriptionField = document.getElementById('meta_description');
        const titlePreview = document.querySelector('[data-preview="title"]');
        const descriptionPreview = document.querySelector('[data-preview="description"]');

        const clip = function (text, limit) {
            return text.length > limit ? text.slice(0, limit - 1).trimEnd() + '…' : text;
        };

        const update = function () {
            titlePreview.textContent = clip(titleField.value.trim() || fallbackTitle, 60);
            descriptionPreview.textContent = clip(descriptionField.value.trim() || fallbackDescription, 155);
        };

        titleField.addEventListener('input', update);
        descriptionField.addEventListener('input', update);
        update();
    })();
</script>
@endpush

@extends('layouts.app')

@section('title', 'Upload Media')
@section('eyebrow', 'Content Assets')
@section('page-title', 'Upload Media')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Media Library', 'url' => route('admin.media.index')],
    ['label' => 'Upload Media'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-7">
                <h2 class="h5 fw-black mb-1">Asset details</h2>
                <p class="text-muted mb-4">Upload approved images and PDF files into the shared media library.</p>

                <div class="row g-3">
                    <div class="col-12">
                        @include('admin.partials.image-preview-field', [
                            'name' => 'file',
                            'label' => 'Image or PDF file',
                            'help' => 'Images: JPG, PNG, WebP up to 5 MB and 3000 x 3000. PDFs: up to 10 MB.',
                        ])
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold" for="alt_text">Alt text</label>
                        <input id="alt_text" name="alt_text" class="form-control @error('alt_text') is-invalid @enderror" value="{{ old('alt_text') }}" maxlength="255">
                        <div class="form-text">Required later for content images; optional for PDFs.</div>
                        @error('alt_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="rounded-4 border bg-light p-4 h-100">
                    <div class="metric-icon mb-3"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                    <h3 class="h5 fw-black">Storage rules</h3>
                    <p class="text-muted mb-2">New uploads are stored on the configured media disk under dated library folders.</p>
                    <p class="small text-muted mb-0">The Media Library records file type, size, dimensions, uploader, and public URL metadata.</p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
            <a class="btn btn-outline-tee" href="{{ route('admin.media.index') }}">Cancel</a>
            <button class="btn btn-tee" type="submit">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i>
                Upload Media
            </button>
        </div>
    </form>
@endcomponent
@endsection

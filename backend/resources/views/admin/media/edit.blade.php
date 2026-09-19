@extends('layouts.app')

@section('title', 'Edit Media')
@section('eyebrow', 'Content Assets')
@section('page-title', 'Edit Media')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Media Library', 'url' => route('admin.media.index')],
    ['label' => 'Edit Media'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.media.update', $media) }}">
        @csrf
        @method('PUT')

        @component('admin.partials.page-header', [
            'eyebrow' => 'Asset details',
            'title' => $media->original_name ?: basename($media->path),
            'description' => 'Update alt text and metadata. The stored file itself cannot be changed; upload a new asset instead.',
        ])
        @endcomponent

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold" for="alt_text">Alt text</label>
                        <input id="alt_text" name="alt_text" class="form-control @error('alt_text') is-invalid @enderror" value="{{ old('alt_text', $media->alt_text) }}" maxlength="255">
                        <div class="form-text">Describes the asset for screen readers and SEO.</div>
                        @error('alt_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @foreach (config('media.metadata_fields', []) as $key => $field)
                        <div class="col-12">
                            <label class="form-label fw-bold" for="metadata_{{ $key }}">{{ $field['label'] ?? \Illuminate\Support\Str::headline($key) }}</label>
                            @if (($field['max'] ?? 255) > 255)
                                <textarea id="metadata_{{ $key }}" name="metadata[{{ $key }}]" rows="3" class="form-control @error('metadata.'.$key) is-invalid @enderror" maxlength="{{ $field['max'] ?? 255 }}">{{ old('metadata.'.$key, $media->metadataValue($key)) }}</textarea>
                            @else
                                <input id="metadata_{{ $key }}" name="metadata[{{ $key }}]" class="form-control @error('metadata.'.$key) is-invalid @enderror" value="{{ old('metadata.'.$key, $media->metadataValue($key)) }}" maxlength="{{ $field['max'] ?? 255 }}">
                            @endif
                            @if (! empty($field['help']))
                                <div class="form-text">{{ $field['help'] }}</div>
                            @endif
                            @error('metadata.'.$key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-5">
                <div class="rounded-4 border bg-light p-4 h-100">
                    <div class="image-preview-frame mb-3">
                        @if ($media->is_image)
                            <img src="{{ $media->url }}" alt="{{ $media->alt_text ?: $media->original_name }}">
                        @else
                            <div class="text-center text-muted small px-3">
                                <i class="bi bi-file-earmark-text d-block fs-3 mb-1"></i>
                                {{ $media->mime_type ?: 'File' }}
                            </div>
                        @endif
                    </div>

                    <dl class="row small mb-0">
                        <dt class="col-5 text-muted fw-normal">Path</dt>
                        <dd class="col-7 text-break">
                            <a class="text-decoration-none" href="{{ $media->url }}" target="_blank" rel="noopener">{{ $media->path }}</a>
                        </dd>
                        <dt class="col-5 text-muted fw-normal">Disk</dt>
                        <dd class="col-7">{{ $media->disk }}</dd>
                        <dt class="col-5 text-muted fw-normal">Type</dt>
                        <dd class="col-7">{{ $media->mime_type ?: 'Unknown' }}</dd>
                        <dt class="col-5 text-muted fw-normal">Dimensions</dt>
                        <dd class="col-7">{{ $media->dimensions ?? 'N/A' }}</dd>
                        <dt class="col-5 text-muted fw-normal">Uploaded by</dt>
                        <dd class="col-7">{{ $media->uploadedBy?->name ?? 'System' }}</dd>
                        <dt class="col-5 text-muted fw-normal">Uploaded</dt>
                        <dd class="col-7 mb-0">{{ $media->created_at?->format('d M Y, h:i A') ?? 'Unknown' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="border-top mt-4 pt-4">
            <p class="small fw-bold text-uppercase text-primary mb-1">Usage</p>
            @if (count($usage) === 0)
                <p class="text-muted mb-0">This asset is not referenced by any module and can be deleted safely.</p>
            @else
                <p class="text-muted mb-2">This asset is in use and cannot be deleted until the references below are removed.</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($usage as $entry)
                        @include('admin.partials.status-badge', ['label' => $entry['label'].' ('.$entry['count'].')', 'class' => 'role'])
                    @endforeach
                </div>
            @endif
        </div>

        <div class="d-flex flex-wrap justify-content-between gap-2 border-top mt-4 pt-4">
            <button
                class="btn btn-outline-danger rounded-3 fw-bold"
                type="button"
                data-delete-action="{{ route('admin.media.destroy', $media) }}"
                data-delete-title="Delete {{ $media->original_name ?: basename($media->path) }}?"
                data-delete-message="This permanently removes the library record and the stored file."
                @disabled(count($usage) > 0)
            >
                <i class="bi bi-trash3 me-1"></i>
                Delete Media
            </button>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-tee" href="{{ route('admin.media.index') }}">Cancel</a>
                <button class="btn btn-tee" type="submit">
                    <i class="bi bi-check2-circle me-1"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </form>
@endcomponent
@endsection

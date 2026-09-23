@php
    $model = $model ?? null;
    $titleName = $titleName ?? 'seo_title';
    $descriptionName = $descriptionName ?? 'seo_description';
    $keywordsName = $keywordsName ?? 'seo_keywords';
    $showKeywords = $showKeywords ?? true;
    $heading = $heading ?? 'Search metadata';
    $description = $description ?? null;
@endphp

<div class="border-top mt-4 pt-4">
    <p class="small fw-bold text-uppercase text-primary mb-1">SEO</p>
    <h3 class="h5 fw-black mb-1">{{ $heading }}</h3>
    @if ($description)
        <p class="text-muted mb-3">{{ $description }}</p>
    @else
        <div class="mb-3"></div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-bold" for="{{ $titleName }}">Meta title</label>
            <input id="{{ $titleName }}" name="{{ $titleName }}" class="form-control @error($titleName) is-invalid @enderror" value="{{ old($titleName, data_get($model, $titleName)) }}" maxlength="255">
            @error($titleName)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label fw-bold" for="{{ $descriptionName }}">Meta description</label>
            <textarea id="{{ $descriptionName }}" name="{{ $descriptionName }}" class="form-control @error($descriptionName) is-invalid @enderror" rows="3">{{ old($descriptionName, data_get($model, $descriptionName)) }}</textarea>
            @error($descriptionName)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        @if ($showKeywords)
            <div class="col-12">
                <label class="form-label fw-bold" for="{{ $keywordsName }}">Meta keywords</label>
                <input id="{{ $keywordsName }}" name="{{ $keywordsName }}" class="form-control @error($keywordsName) is-invalid @enderror" value="{{ old($keywordsName, data_get($model, $keywordsName)) }}">
                <div class="form-text">Separate keywords with commas where a module still needs them.</div>
                @error($keywordsName)<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @endif
    </div>
</div>

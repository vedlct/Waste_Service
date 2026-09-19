@php
    $valueName = "settings[{$setting->id}][value]";
    $valueKey = "settings.{$setting->id}.value";
    $publicName = "settings[{$setting->id}][is_public]";
    $publicKey = "settings.{$setting->id}.is_public";
    $fieldId = "setting_{$setting->id}";
    $inputType = $setting->input_type;
    $currentValue = old($valueKey, $setting->form_value);
    $isPublic = (bool) old($publicKey, $setting->is_public);
@endphp

<div class="col-12 col-xl-6">
    <div class="rounded-4 border p-3 h-100">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
            <label class="form-label fw-bold mb-0" for="{{ $fieldId }}">{{ $setting->display_label }}</label>
            <code class="small text-muted">{{ $setting->group }}.{{ $setting->key }}</code>
        </div>

        @if ($inputType === 'boolean')
            <div class="form-check form-switch">
                <input type="hidden" name="{{ $valueName }}" value="0">
                <input class="form-check-input @error($valueKey) is-invalid @enderror" type="checkbox" role="switch" id="{{ $fieldId }}" name="{{ $valueName }}" value="1" @checked((bool) $currentValue)>
                <label class="form-check-label" for="{{ $fieldId }}">Enabled</label>
            </div>
        @elseif ($inputType === 'money')
            <div class="input-group">
                <span class="input-group-text">£</span>
                <input id="{{ $fieldId }}" type="number" step="0.01" min="0" name="{{ $valueName }}" class="form-control @error($valueKey) is-invalid @enderror" value="{{ $currentValue }}">
            </div>
            <div class="form-text">Entered in pounds and stored in pence.</div>
        @elseif ($inputType === 'integer')
            <input id="{{ $fieldId }}" type="number" step="1" min="0" name="{{ $valueName }}" class="form-control @error($valueKey) is-invalid @enderror" value="{{ $currentValue }}">
        @elseif ($inputType === 'text')
            <textarea id="{{ $fieldId }}" name="{{ $valueName }}" rows="3" class="form-control @error($valueKey) is-invalid @enderror" maxlength="2000">{{ $currentValue }}</textarea>
        @else
            <input
                id="{{ $fieldId }}"
                type="{{ $inputType === 'email' ? 'email' : ($inputType === 'url' ? 'url' : 'text') }}"
                name="{{ $valueName }}"
                class="form-control @error($valueKey) is-invalid @enderror"
                value="{{ $currentValue }}"
                maxlength="255"
            >
        @endif

        @error($valueKey)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

        @if ($setting->help_text)
            <div class="form-text">{{ $setting->help_text }}</div>
        @endif

        <div class="form-check form-switch mt-3">
            <input type="hidden" name="{{ $publicName }}" value="0">
            <input class="form-check-input" type="checkbox" role="switch" id="{{ $fieldId }}_public" name="{{ $publicName }}" value="1" @checked($isPublic)>
            <label class="form-check-label small text-muted" for="{{ $fieldId }}_public">Expose through the public API</label>
        </div>
    </div>
</div>

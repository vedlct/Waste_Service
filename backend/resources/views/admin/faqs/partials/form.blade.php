<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">FAQ details</h2>
        <p class="text-muted mb-4">Keep answers short and specific. Customers read these before contacting the office.</p>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-bold" for="question">Question</label>
                <input id="question" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question', $faq->question) }}" required maxlength="255">
                @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="answer">Answer</label>
                <textarea id="answer" name="answer" rows="8" class="form-control @error('answer') is-invalid @enderror" required maxlength="10000">{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4">
            <div class="metric-icon mb-3"><i class="bi bi-patch-question-fill"></i></div>
            <h3 class="h5 fw-black">Placement</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    <label class="form-label fw-bold" for="service_id">Service</label>
                    <select id="service_id" name="service_id" class="form-select select2 @error('service_id') is-invalid @enderror">
                        <option value="">General (FAQ page)</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected((int) old('service_id', $faq->service_id) === $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Assigning a service moves the FAQ onto that service page.</div>
                    @error('service_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    @include('admin.partials.sort-order-field', ['value' => $faq->sort_order ?? 0])
                </div>
                <div class="col-12">
                    @include('admin.partials.publish-toggle', [
                        'name' => 'is_active',
                        'label' => 'Active',
                        'checked' => (bool) ($faq->is_active ?? true),
                        'help' => 'Inactive FAQs stay hidden on the website.',
                    ])
                </div>
                <div class="col-12">
                    <p class="small text-muted mb-0">Deleting a service also deletes the FAQs assigned to it.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.faqs.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save FAQ</button>
</div>

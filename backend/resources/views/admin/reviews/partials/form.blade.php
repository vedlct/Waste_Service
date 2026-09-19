<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Review details</h2>
        <p class="text-muted mb-4">Reviews added here behave the same as ones submitted through the website form.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="reviewer_name">Reviewer name</label>
                <input id="reviewer_name" name="reviewer_name" class="form-control @error('reviewer_name') is-invalid @enderror" value="{{ old('reviewer_name', $review->reviewer_name) }}" required maxlength="255">
                @error('reviewer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="reviewer_email">Reviewer email</label>
                <input id="reviewer_email" type="email" name="reviewer_email" class="form-control @error('reviewer_email') is-invalid @enderror" value="{{ old('reviewer_email', $review->reviewer_email) }}" maxlength="255">
                <div class="form-text">Kept private. Never shown on the website.</div>
                @error('reviewer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="body">Review</label>
                <textarea id="body" name="body" rows="6" class="form-control @error('body') is-invalid @enderror" required maxlength="5000">{{ old('body', $review->body) }}</textarea>
                @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="rating">Rating</label>
                <select id="rating" name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                    @for ($star = config('reviews.max_rating', 5); $star >= 1; $star--)
                        <option value="{{ $star }}" @selected((int) old('rating', $review->rating) === $star)>{{ $star }} star{{ $star === 1 ? '' : 's' }}</option>
                    @endfor
                </select>
                @error('rating')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="source">Source</label>
                <select id="source" name="source" class="form-select select2 @error('source') is-invalid @enderror" required>
                    @foreach (config('reviews.sources') as $value => $label)
                        <option value="{{ $value }}" @selected(old('source', $review->source) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('source')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4">
            <div class="metric-icon mb-3"><i class="bi bi-shield-check"></i></div>
            <h3 class="h5 fw-black">Moderation</h3>

            <div class="row g-3 mt-0">
                <div class="col-12">
                    <label class="form-label fw-bold" for="status">Status</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach (config('reviews.statuses') as $value => $status)
                            <option value="{{ $value }}" @selected(old('status', $review->status) === $value)>{{ $status['label'] }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                    <ul class="list-unstyled small text-muted mt-2 mb-0">
                        @foreach (config('reviews.statuses') as $status)
                            <li class="d-flex gap-2 mb-1">
                                <span class="fw-bold text-nowrap">{{ $status['label'] }}:</span>
                                <span>{{ $status['description'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="service_id">Service</label>
                    <select id="service_id" name="service_id" class="form-select select2 @error('service_id') is-invalid @enderror">
                        <option value="">General (shown site wide)</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected((int) old('service_id', $review->service_id) === $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                @if ($review->exists)
                    <div class="col-12">
                        <dl class="row small mb-0">
                            <dt class="col-6 text-muted fw-normal">Moderated</dt>
                            <dd class="col-6">{{ $review->reviewed_at?->format('d M Y, h:i A') ?? 'Not yet' }}</dd>
                            <dt class="col-6 text-muted fw-normal">Published</dt>
                            <dd class="col-6 mb-0">{{ $review->published_at?->format('d M Y, h:i A') ?? 'Not published' }}</dd>
                        </dl>
                        <p class="small text-muted mt-2 mb-0">Both timestamps follow the status automatically.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.reviews.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save Review</button>
</div>

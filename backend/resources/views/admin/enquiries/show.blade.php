@extends('layouts.app')

@section('title', 'Enquiry Detail')
@section('eyebrow', 'Enquiries')
@section('page-title', 'Enquiry Detail')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Contact Enquiries', 'url' => route('admin.enquiries.index')],
    ['label' => $enquiry->name],
])

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        @component('admin.partials.panel')
            @component('admin.partials.page-header', [
                'eyebrow' => $enquiry->service_label ?: 'General enquiry',
                'title' => $enquiry->name,
                'description' => 'Received ' . $enquiry->created_at?->format('d M Y, h:i A') . ' via ' . $enquiry->sourceLabel() . '.',
            ])
                @slot('actions')
                    @include('admin.enquiries.partials.status', ['enquiry' => $enquiry])
                @endslot
            @endcomponent

            <dl class="row">
                <dt class="col-sm-4 text-muted fw-normal">Email</dt>
                <dd class="col-sm-8"><a class="text-decoration-none" href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></dd>

                <dt class="col-sm-4 text-muted fw-normal">Phone</dt>
                <dd class="col-sm-8"><a class="text-decoration-none" href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a></dd>

                <dt class="col-sm-4 text-muted fw-normal">Service</dt>
                <dd class="col-sm-8">
                    @if ($enquiry->service)
                        <a class="text-decoration-none" href="{{ route('admin.services.edit', $enquiry->service) }}">{{ $enquiry->service->name }}</a>
                    @else
                        {{ $enquiry->service_label ?: 'Not specified' }}
                    @endif
                </dd>

                <dt class="col-sm-4 text-muted fw-normal">Responded</dt>
                <dd class="col-sm-8 mb-0">{{ $enquiry->responded_at?->format('d M Y, h:i A') ?? 'Not yet' }}</dd>
            </dl>

            <div class="border-top mt-4 pt-4">
                <p class="small fw-bold text-uppercase text-primary mb-1">Message</p>
                <h3 class="h5 fw-black mb-3">What the customer wrote</h3>
                <div class="rounded-4 border bg-light p-4">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $enquiry->message }}</p>
                </div>
            </div>

            <div class="border-top mt-4 pt-4">
                <p class="small fw-bold text-uppercase text-primary mb-1">Submission</p>
                <h3 class="h5 fw-black mb-3">Where it came from</h3>
                <dl class="row small mb-0">
                    <dt class="col-sm-4 text-muted fw-normal">IP address</dt>
                    <dd class="col-sm-8">{{ $enquiry->ip_address ?: 'Not recorded' }}</dd>
                    <dt class="col-sm-4 text-muted fw-normal">User agent</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $enquiry->user_agent ?: 'Not recorded' }}</dd>
                </dl>
            </div>
        @endcomponent
    </div>

    <div class="col-lg-5">
        @component('admin.partials.panel')
            <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}">
                @csrf
                @method('PUT')

                <div class="metric-icon mb-3"><i class="bi bi-clipboard-check"></i></div>
                <h2 class="h5 fw-black mb-1">Workflow</h2>
                <p class="text-muted mb-4">The customer's own details and message are kept exactly as submitted.</p>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold" for="status">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach (config('enquiries.statuses') as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $enquiry->status) === $value)>{{ $status['label'] }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        <ul class="list-unstyled small text-muted mt-2 mb-0">
                            @foreach (config('enquiries.statuses') as $status)
                                <li class="d-flex gap-2 mb-1">
                                    <span class="fw-bold text-nowrap">{{ $status['label'] }}:</span>
                                    <span>{{ $status['description'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold" for="assigned_to">Assigned to</label>
                        <select id="assigned_to" name="assigned_to" class="form-select select2 @error('assigned_to') is-invalid @enderror">
                            <option value="">Unassigned</option>
                            @foreach ($assignees as $assignee)
                                <option value="{{ $assignee->id }}" @selected((int) old('assigned_to', $enquiry->assigned_to) === $assignee->id)>{{ $assignee->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <p class="small text-muted mb-0">Marking the enquiry Responded or Closed stamps the responded date automatically.</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between gap-2 border-top mt-4 pt-4">
                    <a class="btn btn-outline-tee" href="{{ route('admin.enquiries.index') }}">Back</a>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-outline-tee" href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: your enquiry') }}">
                            <i class="bi bi-envelope me-1"></i>
                            Reply
                        </a>
                        <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save</button>
                    </div>
                </div>
            </form>
        @endcomponent
    </div>
</div>
@endsection

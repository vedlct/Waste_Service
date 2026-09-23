@extends('layouts.app')

@section('title', 'Edit Service')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Edit Service')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => $service->name],
])

@section('content')
@component('admin.partials.panel')
    @component('admin.partials.page-header', [
        'eyebrow' => 'Editing',
        'title' => $service->name,
        'description' => 'Update the service details, hero, publishing, and cross-links.',
    ])
        @slot('actions')
            <a class="btn btn-outline-tee" href="{{ route('admin.services.blocks.index', $service) }}">
                <i class="bi bi-layout-text-window-reverse me-1"></i>
                Content Blocks
            </a>
        @endslot
    @endcomponent

    <form method="POST" action="{{ route('admin.services.update', $service) }}">
        @csrf
        @method('PUT')
        @include('admin.services.partials.form', ['mode' => 'edit'])
    </form>
@endcomponent
@endsection

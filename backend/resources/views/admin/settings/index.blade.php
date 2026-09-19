@extends('layouts.app')

@section('title', 'Site Settings')
@section('eyebrow', 'Configuration')
@section('page-title', 'Site Settings')
@php($breadcrumbs = array_values(array_filter([
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Site Settings', 'url' => $activeGroup ? route('admin.settings.index') : null],
    $activeGroup ? ['label' => $groups[$activeGroup]['label']] : null,
])))

@section('content')
@component('admin.partials.panel')
    @if ($activeGroup === null)
        @include('admin.partials.empty-state', [
            'icon' => 'sliders',
            'title' => 'No settings found',
            'message' => 'Run the project seeder to create the default site settings.',
        ])
    @else
        @component('admin.partials.page-header', [
            'eyebrow' => 'Site configuration',
            'title' => $groups[$activeGroup]['label'].' settings',
            'description' => $groups[$activeGroup]['description'] ?? 'Update the values used across the website and admin panel.',
        ])
        @endcomponent

        <ul class="nav nav-pills flex-wrap gap-2 mb-4">
            @foreach ($groups as $key => $group)
                <li class="nav-item">
                    <a class="nav-link rounded-3 fw-bold {{ $key === $activeGroup ? 'active' : 'text-muted' }}" href="{{ route('admin.settings.index', ['group' => $key]) }}">
                        <i class="bi bi-{{ $group['icon'] }} me-1"></i>
                        {{ $group['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        @if ($settings->isEmpty())
            @include('admin.partials.empty-state', [
                'icon' => $groups[$activeGroup]['icon'],
                'title' => 'No settings in this group',
                'message' => 'Settings are seeded from the project seeder. Add them there to manage the group here.',
            ])
        @else
            <form method="POST" action="{{ route('admin.settings.update', ['group' => $activeGroup]) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    @foreach ($settings as $setting)
                        @include('admin.settings.partials.field', ['setting' => $setting])
                    @endforeach
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
                    <a class="btn btn-outline-tee" href="{{ route('admin.settings.index', ['group' => $activeGroup]) }}">Reset</a>
                    <button class="btn btn-tee" type="submit">
                        <i class="bi bi-check2-circle me-1"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        @endif
    @endif
@endcomponent
@endsection

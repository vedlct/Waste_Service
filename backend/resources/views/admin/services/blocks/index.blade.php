@extends('layouts.app')

@section('title', 'Content Blocks')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Content Blocks')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => $service->name, 'url' => route('admin.services.edit', $service)],
    ['label' => 'Content Blocks'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => $service->name,
            'title' => 'Content blocks',
            'description' => 'Blocks are the stacked sections of the service page, rendered in sort order.',
        ])
            @slot('actions')
                <a class="btn btn-outline-tee" href="{{ route('admin.services.edit', $service) }}">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Service
                </a>
                <a class="btn btn-tee" href="{{ route('admin.services.blocks.create', $service) }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Block
                </a>
            @endslot
        @endcomponent
    @endslot

    @if ($blocks->isEmpty())
        @include('admin.partials.empty-state', [
            'icon' => 'layout-text-window-reverse',
            'title' => 'No content blocks yet',
            'message' => 'Add a block to build the service page sections.',
        ])
    @else
        <table class="table table-hover w-100">
            <thead>
                <tr>
                    <th>Media</th>
                    <th>Block</th>
                    <th>Component</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Sort</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blocks as $block)
                    <tr>
                        <td>
                            @if ($block->media)
                                @include('admin.media.partials.preview', ['media' => $block->media])
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $block->heading ?: $block->block_key }}</div>
                            <div class="small text-muted">{{ $block->block_key }}</div>
                        </td>
                        <td>{{ config('service_cms.block_components')[$block->component] ?? $block->component }}</td>
                        <td>
                            @include('admin.partials.status-badge', [
                                'label' => $block->is_enabled ? 'Enabled' : 'Disabled',
                                'class' => $block->is_enabled ? 'active' : 'muted',
                            ])
                        </td>
                        <td>{{ $block->items_count }}</td>
                        <td>{{ $block->sort_order }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.services.blocks.edit', [$service, $block]) }}" title="Edit block"><i class="bi bi-pencil-square"></i></a>
                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    type="button"
                                    title="Delete block"
                                    data-delete-action="{{ route('admin.services.blocks.destroy', [$service, $block]) }}"
                                    data-delete-title="Delete {{ $block->heading ?: $block->block_key }}?"
                                    data-delete-message="This also removes the {{ $block->items_count }} item(s) inside the block."
                                >
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endcomponent
@endsection

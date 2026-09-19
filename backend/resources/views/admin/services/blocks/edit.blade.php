@extends('layouts.app')

@section('title', 'Edit Content Block')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Edit Content Block')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => $service->name, 'url' => route('admin.services.edit', $service)],
    ['label' => 'Content Blocks', 'url' => route('admin.services.blocks.index', $service)],
    ['label' => $block->heading ?: $block->block_key],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.services.blocks.update', [$service, $block]) }}">
        @csrf
        @method('PUT')
        @include('admin.services.blocks.partials.form')
    </form>
@endcomponent

@component('admin.partials.table-card', ['class' => 'p-4 mt-4'])
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Block items',
            'title' => 'Items inside this block',
            'description' => 'Bullets, features, or cards rendered by the block component.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.services.blocks.items.create', [$service, $block]) }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Item
                </a>
            @endslot
        @endcomponent
    @endslot

    @if ($items->isEmpty())
        @include('admin.partials.empty-state', [
            'icon' => 'list-ul',
            'title' => 'No items yet',
            'message' => 'Add items if this block renders a list, feature grid, or card row.',
        ])
    @else
        <table class="table table-hover w-100">
            <thead>
                <tr>
                    <th>Media</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Sort</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>
                            @if ($item->media)
                                @include('admin.media.partials.preview', ['media' => $item->media])
                            @elseif ($item->icon)
                                <i class="bi bi-{{ $item->icon }} fs-4 text-primary"></i>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $item->title }}</div>
                            @if ($item->subtitle)
                                <div class="small text-muted">{{ $item->subtitle }}</div>
                            @endif
                        </td>
                        <td>
                            @include('admin.partials.status-badge', [
                                'label' => $item->is_enabled ? 'Enabled' : 'Disabled',
                                'class' => $item->is_enabled ? 'active' : 'muted',
                            ])
                        </td>
                        <td>{{ $item->sort_order }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a class="btn btn-sm btn-outline-tee" href="{{ route('admin.services.blocks.items.edit', [$service, $block, $item]) }}" title="Edit item"><i class="bi bi-pencil-square"></i></a>
                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    type="button"
                                    title="Delete item"
                                    data-delete-action="{{ route('admin.services.blocks.items.destroy', [$service, $block, $item]) }}"
                                    data-delete-title="Delete {{ $item->title }}?"
                                    data-delete-message="This permanently removes the block item."
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

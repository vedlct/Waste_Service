@extends('layouts.app')

@section('title', 'Edit Block Item')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Edit Block Item')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => $service->name, 'url' => route('admin.services.edit', $service)],
    ['label' => $block->heading ?: $block->block_key, 'url' => route('admin.services.blocks.edit', [$service, $block])],
    ['label' => $item->title],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.services.blocks.items.update', [$service, $block, $item]) }}">
        @csrf
        @method('PUT')
        @include('admin.services.blocks.items.partials.form')
    </form>
@endcomponent
@endsection

@extends('layouts.app')

@section('title', 'Add Content Block')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Add Content Block')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => $service->name, 'url' => route('admin.services.edit', $service)],
    ['label' => 'Content Blocks', 'url' => route('admin.services.blocks.index', $service)],
    ['label' => 'Add Block'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.services.blocks.store', $service) }}">
        @csrf
        @include('admin.services.blocks.partials.form')
    </form>
@endcomponent
@endsection

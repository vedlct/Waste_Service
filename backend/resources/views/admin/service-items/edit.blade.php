@extends('layouts.app')

@section('title', 'Edit Service Item')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Edit Service Item')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Service Items', 'url' => route('admin.service-items.index')],
    ['label' => $item->name],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.service-items.update', $item) }}">
        @csrf
        @method('PUT')
        @include('admin.service-items.partials.form')
    </form>
@endcomponent
@endsection

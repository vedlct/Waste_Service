@extends('layouts.app')

@section('title', 'Add Service Item')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Add Service Item')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Service Items', 'url' => route('admin.service-items.index')],
    ['label' => 'Add Item'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.service-items.store') }}">
        @csrf
        @include('admin.service-items.partials.form')
    </form>
@endcomponent
@endsection

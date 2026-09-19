@extends('layouts.app')

@section('title', 'Add Service Category')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Add Service Category')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Service Categories', 'url' => route('admin.service-categories.index')],
    ['label' => 'Add Category'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.service-categories.store') }}">
        @csrf
        @include('admin.service-categories.partials.form')
    </form>
@endcomponent
@endsection

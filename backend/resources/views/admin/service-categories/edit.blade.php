@extends('layouts.app')

@section('title', 'Edit Service Category')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Edit Service Category')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Service Categories', 'url' => route('admin.service-categories.index')],
    ['label' => 'Edit Category'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.service-categories.update', $category) }}">
        @csrf
        @method('PUT')
        @include('admin.service-categories.partials.form')
    </form>
@endcomponent
@endsection

@extends('layouts.app')

@section('title', 'Edit Price Category')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Edit Price Category')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Price Categories', 'url' => route('admin.price-categories.index')],
    ['label' => $category->name],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.price-categories.update', $category) }}">
        @csrf
        @method('PUT')
        @include('admin.price-categories.partials.form')
    </form>
@endcomponent
@endsection

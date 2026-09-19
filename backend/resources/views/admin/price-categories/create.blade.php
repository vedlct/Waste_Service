@extends('layouts.app')

@section('title', 'Add Price Category')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Add Price Category')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Price Categories', 'url' => route('admin.price-categories.index')],
    ['label' => 'Add Category'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.price-categories.store') }}">
        @csrf
        @include('admin.price-categories.partials.form')
    </form>
@endcomponent
@endsection

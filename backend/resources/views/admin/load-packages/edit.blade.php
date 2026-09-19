@extends('layouts.app')

@section('title', 'Edit Load Package')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Edit Load Package')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Load Packages', 'url' => route('admin.load-packages.index')],
    ['label' => $package->name],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.load-packages.update', $package) }}">
        @csrf
        @method('PUT')
        @include('admin.load-packages.partials.form')
    </form>
@endcomponent
@endsection

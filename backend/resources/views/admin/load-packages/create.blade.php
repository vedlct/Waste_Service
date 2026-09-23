@extends('layouts.app')

@section('title', 'Add Load Package')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Add Load Package')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Load Packages', 'url' => route('admin.load-packages.index')],
    ['label' => 'Add Package'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.load-packages.store') }}">
        @csrf
        @include('admin.load-packages.partials.form')
    </form>
@endcomponent
@endsection

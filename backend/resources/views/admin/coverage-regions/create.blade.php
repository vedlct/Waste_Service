@extends('layouts.app')

@section('title', 'Add Coverage Region')
@section('eyebrow', 'Coverage')
@section('page-title', 'Add Coverage Region')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Coverage Regions', 'url' => route('admin.coverage-regions.index')],
    ['label' => 'Add Region'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.coverage-regions.store') }}">
        @csrf
        @include('admin.coverage-regions.partials.form')
    </form>
@endcomponent
@endsection

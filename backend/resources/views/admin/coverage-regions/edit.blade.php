@extends('layouts.app')

@section('title', 'Edit Coverage Region')
@section('eyebrow', 'Coverage')
@section('page-title', 'Edit Coverage Region')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Coverage Regions', 'url' => route('admin.coverage-regions.index')],
    ['label' => $region->name],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.coverage-regions.update', $region) }}">
        @csrf
        @method('PUT')
        @include('admin.coverage-regions.partials.form')
    </form>
@endcomponent
@endsection

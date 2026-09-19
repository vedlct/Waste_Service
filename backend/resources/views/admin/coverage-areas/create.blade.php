@extends('layouts.app')

@section('title', 'Add Coverage Area')
@section('eyebrow', 'Coverage')
@section('page-title', 'Add Coverage Area')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Coverage Areas', 'url' => route('admin.coverage-areas.index')],
    ['label' => 'Add Area'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.coverage-areas.store') }}">
        @csrf
        @include('admin.coverage-areas.partials.form')
    </form>
@endcomponent
@endsection

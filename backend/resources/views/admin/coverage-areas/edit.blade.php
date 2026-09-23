@extends('layouts.app')

@section('title', 'Edit Coverage Area')
@section('eyebrow', 'Coverage')
@section('page-title', 'Edit Coverage Area')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Coverage Areas', 'url' => route('admin.coverage-areas.index')],
    ['label' => $area->name],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.coverage-areas.update', $area) }}">
        @csrf
        @method('PUT')
        @include('admin.coverage-areas.partials.form')
    </form>
@endcomponent
@endsection

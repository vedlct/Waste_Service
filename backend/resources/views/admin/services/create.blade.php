@extends('layouts.app')

@section('title', 'Add Service')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Add Service')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => 'Add Service'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.services.store') }}">
        @csrf
        @include('admin.services.partials.form', ['mode' => 'create'])
    </form>
@endcomponent
@endsection

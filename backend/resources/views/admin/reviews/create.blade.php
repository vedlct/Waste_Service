@extends('layouts.app')

@section('title', 'Add Review')
@section('eyebrow', 'Content')
@section('page-title', 'Add Review')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Reviews', 'url' => route('admin.reviews.index')],
    ['label' => 'Add Review'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.reviews.store') }}">
        @csrf
        @include('admin.reviews.partials.form')
    </form>
@endcomponent
@endsection

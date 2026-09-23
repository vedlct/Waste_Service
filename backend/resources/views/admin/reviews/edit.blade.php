@extends('layouts.app')

@section('title', 'Edit Review')
@section('eyebrow', 'Content')
@section('page-title', 'Edit Review')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Reviews', 'url' => route('admin.reviews.index')],
    ['label' => $review->reviewer_name],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
        @csrf
        @method('PUT')
        @include('admin.reviews.partials.form')
    </form>
@endcomponent
@endsection

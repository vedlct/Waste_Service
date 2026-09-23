@extends('layouts.app')

@section('title', 'Add FAQ')
@section('eyebrow', 'Content')
@section('page-title', 'Add FAQ')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'FAQs', 'url' => route('admin.faqs.index')],
    ['label' => 'Add FAQ'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.faqs.store') }}">
        @csrf
        @include('admin.faqs.partials.form')
    </form>
@endcomponent
@endsection

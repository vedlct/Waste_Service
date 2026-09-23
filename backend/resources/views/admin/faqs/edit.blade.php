@extends('layouts.app')

@section('title', 'Edit FAQ')
@section('eyebrow', 'Content')
@section('page-title', 'Edit FAQ')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'FAQs', 'url' => route('admin.faqs.index')],
    ['label' => 'Edit FAQ'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
        @csrf
        @method('PUT')
        @include('admin.faqs.partials.form')
    </form>
@endcomponent
@endsection

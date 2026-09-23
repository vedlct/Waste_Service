@extends('layouts.app')

@section('title', 'Edit Extra Charge')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Edit Extra Charge')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Extra Charges', 'url' => route('admin.extra-charges.index')],
    ['label' => $charge->name],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.extra-charges.update', $charge) }}">
        @csrf
        @method('PUT')
        @include('admin.extra-charges.partials.form')
    </form>
@endcomponent
@endsection

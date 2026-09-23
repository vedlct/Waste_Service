@extends('layouts.app')

@section('title', 'Add Extra Charge')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Add Extra Charge')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Extra Charges', 'url' => route('admin.extra-charges.index')],
    ['label' => 'Add Charge'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.extra-charges.store') }}">
        @csrf
        @include('admin.extra-charges.partials.form')
    </form>
@endcomponent
@endsection

@extends('layouts.app')

@section('title', 'Create User')
@section('eyebrow', 'User Management')
@section('page-title', 'Create User')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Users', 'url' => route('admin.users.index')],
    ['label' => 'Create User'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users.partials.form', ['mode' => 'create'])
    </form>
@endcomponent
@endsection

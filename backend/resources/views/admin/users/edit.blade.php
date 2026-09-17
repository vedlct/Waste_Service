@extends('layouts.app')

@section('title', 'Edit User')
@section('eyebrow', 'User Management')
@section('page-title', 'Edit User')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Users', 'url' => route('admin.users.index')],
    ['label' => 'Edit User'],
])

@section('content')
@component('admin.partials.panel')
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('admin.users.partials.form', ['mode' => 'edit'])
    </form>
@endcomponent
@endsection

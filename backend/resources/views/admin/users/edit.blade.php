@extends('layouts.app')

@section('title', 'Edit User')
@section('eyebrow', 'User Management')
@section('page-title', 'Edit User')

@section('content')
<div class="page-panel p-4">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('admin.users.partials.form', ['mode' => 'edit'])
    </form>
</div>
@endsection

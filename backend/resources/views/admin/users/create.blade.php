@extends('layouts.app')

@section('title', 'Create User')
@section('eyebrow', 'User Management')
@section('page-title', 'Create User')

@section('content')
<div class="page-panel p-4">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users.partials.form', ['mode' => 'create'])
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container">
    <h4>Create User</h4>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        @include('in.admin.users.form')

        <button class="btn btn-success">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

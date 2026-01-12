@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container">
    <h4>Edit User</h4>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf @method('PUT')

        @include('in.admin.users.form', ['user' => $user])

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

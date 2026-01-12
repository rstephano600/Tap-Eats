@extends('layouts.app')

@section('title', 'Assign Permission to User')

@section('content')
<div class="container">
    <h4>Assign Permission to User</h4>

    <form method="POST" action="{{ route('user-permissions.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">User</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Permission</label>
            <select name="permission_id" class="form-select" required>
                <option value="">-- Select Permission --</option>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="grant">Grant</option>
                <option value="revoke">Revoke</option>
            </select>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('user-permissions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

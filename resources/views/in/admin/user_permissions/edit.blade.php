@extends('layouts.app')

@section('title', 'Edit User Permission')

@section('content')
<div class="container">
    <h4>Edit User Permission</h4>

    <form method="POST" action="{{ route('user-permissions.update', $userPermission) }}">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">User</label>
            <input type="text" class="form-control" value="{{ $userPermission->user->name }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Permission</label>
            <select name="permission_id" class="form-select">
                @foreach($permissions as $permission)
                    <option value="{{ $permission->id }}"
                        {{ $userPermission->permission_id == $permission->id ? 'selected' : '' }}>
                        {{ $permission->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="grant" {{ $userPermission->type=='grant'?'selected':'' }}>Grant</option>
                <option value="revoke" {{ $userPermission->type=='revoke'?'selected':'' }}>Revoke</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ $userPermission->status=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ $userPermission->status=='inactive'?'selected':'' }}>Inactive</option>
                <option value="locked" {{ $userPermission->status=='locked'?'selected':'' }}>Locked</option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('user-permissions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

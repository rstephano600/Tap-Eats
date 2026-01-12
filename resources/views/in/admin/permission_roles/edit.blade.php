@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Role Permission</h4>

    <form method="POST" action="{{ route('permission-roles.update', $permissionRole->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" disabled>
                <option>{{ $permissionRole->role->name }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Permission</label>
            <select class="form-select" disabled>
                <option>{{ $permissionRole->permission->name }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ $permissionRole->status == 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive" {{ $permissionRole->status == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
                <option value="locked" {{ $permissionRole->status == 'locked' ? 'selected' : '' }}>
                    Locked
                </option>
            </select>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('permission-roles.index') }}" class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection

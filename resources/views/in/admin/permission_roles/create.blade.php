@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Assign Permission to Role</h4>

    <form method="POST" action="{{ route('permission-roles.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Permission</label>
            <select name="permission_id" class="form-select" required>
                <option value="">-- Select Permission --</option>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->id }}">
                        {{ $permission->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('permission-roles.index') }}" class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Assignment')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('role-users.index') }}" class="text-darkblue">Assignments</a></li>
            <li class="breadcrumb-item active text-muted">Edit Assignment</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-darkblue text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-accent"></i> Update User Assignment</h5>
        </div>
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="{{ route('role-users.update', $roleUser) }}">
                @csrf 
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-darkblue">User</label>
                        <div class="form-control form-control-lg bg-light">
                            <i class="bi bi-person-fill me-2 text-muted"></i> {{ $roleUser->user->name }}
                        </div>
                        <input type="hidden" name="user_id" value="{{ $roleUser->user_id }}">
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-darkblue">Role</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
                            <select name="role_id" class="form-select form-select-lg">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $roleUser->role_id == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option >
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-darkblue">Assignment Status</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-activity"></i></span>
                            <select name="status" class="form-select form-select-lg">
                                <option value="active" {{ $roleUser->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $roleUser->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-25">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('role-users.index') }}" class="btn btn-light border px-4">Cancel</a>
                    <button type="submit" class="btn btn-accent px-5 shadow-sm fw-bold">
                        <i class="bi bi-save me-1"></i> Update Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Assign Role')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('role-users.index') }}" class="text-darkblue">Assignments</a></li>
            <li class="breadcrumb-item active">New Assignment</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-darkblue text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2 text-accent"></i> Assign Role to User</h5>
        </div>
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="{{ route('role-users.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Select User</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <select name="user_id" class="form-select form-select-lg @error('user_id') is-invalid @enderror" required>
                                <option value="">Search for a user...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Assign Role</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                            <select name="role_id" class="form-select form-select-lg @error('role_id') is-invalid @enderror" required>
                                <option value="">Choose a role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4 opacity-25">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('role-users.index') }}" class="btn btn-light border px-4">Cancel</a>
                    <button type="submit" class="btn btn-accent px-5 shadow-sm fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Confirm Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
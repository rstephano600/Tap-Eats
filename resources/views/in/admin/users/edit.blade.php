@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-gear"></i> Edit User: {{ $user->name }}</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information Section -->
                        <h5 class="mb-3 border-bottom pb-2">
                            <i class="bi bi-person-badge"></i> Personal Information
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   placeholder="+1-555-0123">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Section -->
                        <h5 class="mb-3 border-bottom pb-2 mt-4">
                            <i class="bi bi-lock"></i> Change Password
                        </h5>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Leave password fields empty to keep the current password
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
                        </div>

                        <!-- Organization Section -->
                        <h5 class="mb-3 border-bottom pb-2 mt-4">
                            <i class="bi bi-building"></i> Organization
                        </h5>

                        @if($suppliers->isNotEmpty())
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">
                                    Supplier 
                                    @if(auth()->user()->isSuperAdmin())
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" 
                                        name="supplier_id"
                                        {{ auth()->user()->isSuperAdmin() ? 'required' : '' }}>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                                {{ old('supplier_id', $user->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Roles Section -->
                        <h5 class="mb-3 border-bottom pb-2 mt-4">
                            <i class="bi bi-shield-check"></i> Roles & Permissions <span class="text-danger">*</span>
                        </h5>

                        <div class="mb-3">
                            @error('roles')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="roles[]" 
                                                   value="{{ $role->id }}" 
                                                   id="role_{{ $role->id }}"
                                                   {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                <strong>{{ ucwords(str_replace('_', ' ', $role->name)) }}</strong>
                                                @if($role->name === 'super_admin')
                                                    <span class="badge bg-danger ms-1">System Role</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status Section -->
                        <h5 class="mb-3 border-bottom pb-2 mt-4">
                            <i class="bi bi-toggle-on"></i> Status
                        </h5>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active User</strong>
                                    <br>
                                    <small class="text-muted">Enable this user to access the system</small>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <!-- User Info Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-info-circle"></i> User Information
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-sm-5">User ID:</dt>
                        <dd class="col-sm-7">#{{ $user->id }}</dd>

                        <dt class="col-sm-5">Created:</dt>
                        <dd class="col-sm-7">{{ $user->created_at->format('M d, Y') }}</dd>

                        <dt class="col-sm-5">Last Updated:</dt>
                        <dd class="col-sm-7">{{ $user->updated_at->diffForHumans() }}</dd>

                        <dt class="col-sm-5">Email Verified:</dt>
                        <dd class="col-sm-7">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-warning">No</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5">Current Status:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>

                        @if($user->supplier)
                            <dt class="col-sm-5">Supplier:</dt>
                            <dd class="col-sm-7">{{ $user->supplier->name }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Current Roles Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-shield-check"></i> Current Roles
                </div>
                <div class="card-body">
                    @forelse($user->roles as $role)
                        <span class="badge bg-primary me-1 mb-1">
                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @empty
                        <p class="text-muted mb-0 small">No roles assigned</p>
                    @endforelse
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <i class="bi bi-exclamation-triangle"></i> Important Notes
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Leave password fields empty to keep current password</li>
                        <li>Changing email may require re-verification</li>
                        <li>At least one role must be selected</li>
                        <li>Inactive users cannot access the system</li>
                        @if($user->isSuperAdmin())
                            <li class="text-danger"><strong>This is a Super Admin user</strong></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-circle"></i> User Details</h2>
        <div class="btn-group">
            @if(!($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()))
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit User
                </a>
            @endif
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main User Information -->
        <div class="col-lg-8">
            <!-- Profile Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-large me-3">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="mb-1">{{ $user->name }}</h3>
                            <div class="d-flex gap-2 flex-wrap">
                                @forelse($user->roles as $role)
                                    <span class="badge bg-primary">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @empty
                                    <span class="badge bg-secondary">No roles assigned</span>
                                @endforelse
                                
                                @if($user->isSuperAdmin())
                                    <span class="badge bg-danger">Super Admin</span>
                                @endif

                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Email Address</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-envelope me-2 text-primary"></i>
                                <strong>{{ $user->email }}</strong>
                                @if($user->email_verified_at)
                                    <i class="bi bi-check-circle-fill text-success ms-2" title="Email verified"></i>
                                @else
                                    <i class="bi bi-x-circle-fill text-warning ms-2" title="Email not verified"></i>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Phone Number</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-telephone me-2 text-primary"></i>
                                <strong>{{ $user->phone ?? 'Not provided' }}</strong>
                            </div>
                        </div>

                        @if($user->supplier)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-1">Supplier</label>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-building me-2 text-primary"></i>
                                    <strong>{{ $user->supplier->name }}</strong>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Member Since</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>
                                <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                                <span class="text-muted ms-2 small">({{ $user->created_at->diffForHumans() }})</span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Last Updated</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history me-2 text-primary"></i>
                                <strong>{{ $user->updated_at->format('M d, Y') }}</strong>
                                <span class="text-muted ms-2 small">({{ $user->updated_at->diffForHumans() }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Roles & Permissions</h5>
                </div>
                <div class="card-body">
                    @forelse($user->roles as $role)
                        <div class="role-section mb-4 pb-4 border-bottom">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-shield-fill"></i> 
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                @if($role->name === 'super_admin')
                                    <span class="badge bg-danger ms-2">System Role</span>
                                @endif
                            </h6>
                            
                            @if($role->permissions->count() > 0)
                                <div class="row">
                                    @foreach($role->permissions as $permission)
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                                <div>
                                                    <strong>{{ ucwords(str_replace(['_', '.'], [' ', ' '], $permission->name)) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">No specific permissions assigned to this role</p>
                            @endif
                        </div>
                    @empty
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i> No roles assigned to this user
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Activity Summary Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-activity"></i> Activity Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <i class="bi bi-cart-check display-4 text-primary mb-2"></i>
                                <h3 class="mb-0">{{ $user->assignedOrders->count() }}</h3>
                                <p class="text-muted mb-0 small">Assigned Orders</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <i class="bi bi-receipt display-4 text-success mb-2"></i>
                                <h3 class="mb-0">{{ $user->customerOrders->count() }}</h3>
                                <p class="text-muted mb-0 small">Customer Orders</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <i class="bi bi-calendar-event display-4 text-warning mb-2"></i>
                                <h3 class="mb-0">{{ $user->created_at->diffInDays(now()) }}</h3>
                                <p class="text-muted mb-0 small">Days Active</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()))
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit User
                            </a>
                        @endif
                        
                        <button type="button" 
                                class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }} btn-sm btn-toggle-status"
                                data-user-id="{{ $user->id }}">
                            <i class="bi bi-{{ $user->is_active ? 'x-circle' : 'check-circle' }}"></i>
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                        </button>

                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#rolesModal">
                            <i class="bi bi-shield-plus"></i> Manage Roles
                        </button>

                        @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-trash"></i> Delete User
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-{{ $user->is_active ? 'success' : 'secondary' }} text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Account Status</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-6">Status:</dt>
                        <dd class="col-6">
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>

                        <dt class="col-6">Email Verified:</dt>
                        <dd class="col-6">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-warning">No</span>
                            @endif
                        </dd>

                        <dt class="col-6">User ID:</dt>
                        <dd class="col-6">#{{ $user->id }}</dd>

                        <dt class="col-6">Roles Count:</dt>
                        <dd class="col-6">{{ $user->roles->count() }}</dd>
                    </dl>
                </div>
            </div>

            <!-- System Information Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-gear"></i> System Information</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-6">Created:</dt>
                        <dd class="col-6">{{ $user->created_at->format('Y-m-d H:i') }}</dd>

                        <dt class="col-6">Updated:</dt>
                        <dd class="col-6">{{ $user->updated_at->format('Y-m-d H:i') }}</dd>

                        @if($user->email_verified_at)
                            <dt class="col-6">Verified:</dt>
                            <dd class="col-6">{{ $user->email_verified_at->format('Y-m-d H:i') }}</dd>
                        @endif

                        @if($user->supplier)
                            <dt class="col-6">Supplier ID:</dt>
                            <dd class="col-6">#{{ $user->supplier_id }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Roles Management Modal -->
<div class="modal fade" id="rolesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-shield-plus"></i> Manage User Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rolesForm">
                <div class="modal-body">
                    <p class="text-muted">Select roles for <strong>{{ $user->name }}</strong></p>
                    <div id="rolesCheckboxes">
                        <!-- Roles will be loaded here dynamically -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Roles
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 32px;
}

.stat-card {
    padding: 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.role-section:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle user status
    const toggleBtn = document.querySelector('.btn-toggle-status');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            
            fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Load roles in modal (you'll need to implement this based on your needs)
    const rolesModal = document.getElementById('rolesModal');
    if (rolesModal) {
        rolesModal.addEventListener('show.bs.modal', function() {
            // Load current user roles and all available roles
            // This is a placeholder - implement based on your backend
        });
    }
});
</script>
@endpush
@endsection
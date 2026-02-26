@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Staff Management</h2>
                
                <div class="d-flex gap-2">
                    <!-- Supplier Selector -->
                    @if($suppliers->count() > 1)
                        <select class="form-select" onchange="window.location.href='{{ route('admin.staff.index') }}?supplier_id=' + this.value">
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ $supplier->id == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->business_name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    
                    @can('create_staff')
                        <a href="{{ route('admin.staff.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Staff Member
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Staff Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($member->profile_image)
                                                <img src="{{ asset('storage/' . $member->profile_image) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 40px; height: 40px;">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $member->name }}</strong>
                                                @if($member->pivot_data->is_primary)
                                                    <span class="badge bg-warning ms-1">Primary</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>
                                        @can('assign_roles')
                                            <select class="form-select form-select-sm" 
                                                    onchange="updateRole({{ $member->id }}, this.value, {{ $supplier->id }})">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" 
                                                            {{ $member->current_role?->id == $role->id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="badge bg-info">
                                                {{ ucwords(str_replace('_', ' ', $member->current_role?->name ?? 'N/A')) }}
                                            </span>
                                        @endcan
                                    </td>
                                    <td>
                                        @if($member->pivot_data->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->pivot_data->joined_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('edit_staff')
                                                <form action="{{ route('admin.staff.toggle-status', $member) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" 
                                                            title="{{ $member->pivot_data->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="bi bi-{{ $member->pivot_data->is_active ? 'pause' : 'play' }}-circle"></i>
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('delete_staff')
                                                @if(!$member->isSuperAdmin() && $member->id != auth()->id())
                                                    <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to remove this staff member?')" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">No staff members found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateRole(userId, roleId, supplierId) {
    if (!confirm('Are you sure you want to change this user\'s role?')) {
        return;
    }
    
    fetch(`/admin/staff/${userId}/role`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            supplier_id: supplierId,
            role_id: roleId
        })
    })
    .then(response => response.json())
    .then(data => {
        location.reload();
    })
    .catch(error => {
        alert('Failed to update role');
        console.error(error);
    });
}
</script>
@endsection
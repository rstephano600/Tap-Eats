@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-darkblue"><i class="bi bi-shield-lock me-2"></i> Roles</h5>
                <a href="{{ route('roles.create') }}" class="btn btn-accent shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> New Role
                </a>
            </div>
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $role->name }}</td>
                                <td><code class="text-primary">{{ $role->slug }}</code></td>
                                <td>
                                    @php
                                        $badgeClass = match($role->status) {
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'locked' => 'warning',
                                            'deleted' => 'danger',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $badgeClass }} px-3">
                                        {{ ucfirst($role->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('roles.destroy', $role) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this role?')"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 px-3">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
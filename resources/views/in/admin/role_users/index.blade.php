@extends('layouts.app')

@section('title', 'User Role Assignments')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-people me-2 text-accent"></i> User Role Assignments
            </h5>
            <a href="{{ route('role-users.create') }}" class="btn btn-accent shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Assign New Role
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">User</th>
                            <th>Assigned Role</th>
                            <th>Status</th>
                            <th class="text-end pe-3" width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roleUsers as $item)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-darkblue"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-darkblue">{{ $item->user->name }}</div>
                                        <small class="text-muted">{{ $item->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-shield-check me-1 text-muted"></i> {{ $item->role->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $item->status === 'active' ? 'success' : 'secondary' }} px-3">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('role-users.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('role-users.destroy', $item) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this assignment?')">
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
        </div>
    </div>
</div>
@endsection
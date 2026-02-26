@extends('layouts.app')

@section('title', 'Supplier Users')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-people me-2 text-accent"></i> Supplier Users
            </h5>
            <a href="{{ route('createsuppuserinfo') }}" class="btn btn-accent shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Assign User
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
            <table class="table datatable w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Supplier</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Primary</th>
                            <th>Status</th>
                            <th width="180" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplierUsers as $item)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-darkblue">{{ $item->supplier->business_name ?? '' }}</td>
                            <td>{{ $item->user->name ?? '' }}</td>
                            <td>
                                <span class="badge bg-info rounded-pill px-3">
                                    {{ $item->role->name ?? '' }}
                                </span>
                            </td>
                            <td>
                                @if($item->is_primary)
                                    <span class="badge bg-success rounded-pill px-3">Yes</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3">No</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($item->Status) {
                                        'Active' => 'success',
                                        'Inactive' => 'secondary',
                                        'Locked' => 'warning',
                                        'Deleted' => 'danger',
                                        default => 'info'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} rounded-pill px-3">
                                    {{ $item->Status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('showsuppuserinfo', $item->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('editsuppuserinfo', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('destroysuppuserinfo', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Remove this assignment?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No supplier users found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

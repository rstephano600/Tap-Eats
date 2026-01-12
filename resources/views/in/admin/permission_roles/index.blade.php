@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Role Permissions</h4>
        <a href="{{ route('permission-roles.create') }}" class="btn btn-primary">
            Assign Permission
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Role</th>
                <th>Permission</th>
                <th>Status</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permissionRoles as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->role->name }}</td>
                <td>{{ $item->permission->name }}</td>
                <td>
                    <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('permission-roles.edit', $item->id) }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('permission-roles.destroy', $item->id) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Remove this permission?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No permissions assigned</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $permissionRoles->links() }}
</div>
@endsection

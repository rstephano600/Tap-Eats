@extends('layouts.app')

@section('title', 'User Permissions')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>User Permission Overrides</h4>
        <a href="{{ route('user-permissions.create') }}" class="btn btn-primary">
            Add User Permission
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Permission</th>
                <th>Type</th>
                <th>Status</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($userPermissions as $up)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $up->user->name }}</td>
                    <td>{{ $up->permission->name }}</td>
                    <td>
                        <span class="badge bg-{{ $up->type == 'grant' ? 'success' : 'danger' }}">
                            {{ strtoupper($up->type) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($up->status) }}</td>
                    <td>
                        <a href="{{ route('user-permissions.edit', $up) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <form action="{{ route('user-permissions.destroy', $up) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Remove this override?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No user permissions</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $userPermissions->links() }}
</div>
@endsection

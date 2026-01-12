@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Users</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>User Type</th>
                <th>Status</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->user_type ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ $user->status=='active'?'success':'secondary' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete user?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection

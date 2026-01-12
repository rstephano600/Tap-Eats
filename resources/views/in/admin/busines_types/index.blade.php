@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Service Types</h4>
        <a href="{{ route('busines-types.create') }}" class="btn btn-primary">Add Service Type</a>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($businesTypes as $businesType)
            <tr>
                <td>{{ $businesType->id }}</td>
                <td>{{ $businesType->name }}</td>
                <td>{{ $businesType->slug }}</td>
                <td>{{ $businesType->status }}</td>
                <td>{{ $businesType->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('busines-types.show', $businesType) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('busines-types.edit', $businesType) }}" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $businesTypes->links() }}
</div>
@endsection

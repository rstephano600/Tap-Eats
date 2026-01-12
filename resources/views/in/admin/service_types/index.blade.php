@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Service Types</h4>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">Add Service Type</a>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Status</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceTypes as $serviceType)
            <tr>
                <td>{{ $serviceType->id }}</td>
                <td>{{ $serviceType->name }}</td>
                <td>{{ $serviceType->status }}</td>
                <td>{{ $serviceType->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('service-types.show', $serviceType) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $serviceTypes->links() }}
</div>
@endsection

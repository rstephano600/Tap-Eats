@extends('layouts.app')

@section('content')
<div class="container">
    <h4>{{ $serviceType->name }}</h4>

    <p><strong>Description:</strong> {{ $serviceType->description }}</p>
    <p><strong>Status:</strong> {{ $serviceType->status }}</p>
    <p><strong>Active:</strong> {{ $serviceType->is_active ? 'Yes' : 'No' }}</p>

    <a href="{{ route('Servicetypes') }}" class="btn btn-secondary">Back</a>
</div>
@endsection

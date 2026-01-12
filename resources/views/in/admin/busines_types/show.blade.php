@extends('layouts.app')

@section('content')
<div class="container">
    <h4>{{ $businesType->name }}</h4>

    <p><strong>Description:</strong> {{ $businesType->description }}</p>
    <p><strong>Status:</strong> {{ $businesType->status }}</p>
    <p><strong>Active:</strong> {{ $businesType->is_active ? 'Yes' : 'No' }}</p>

    <a href="{{ route('busines-types.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection

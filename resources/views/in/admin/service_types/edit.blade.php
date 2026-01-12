@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Service Type</h4>

    <form method="POST" action="{{ route('service-types.update', $serviceType) }}">
        @csrf
        @method('PUT')
        @include('in.admin.service_types._form')
        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection

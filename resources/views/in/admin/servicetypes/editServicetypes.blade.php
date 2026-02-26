@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Service Type</h4>

    <form method="POST" action="{{ route('updateServicetypes', $serviceType) }}">
        @csrf
        @include('in.admin.servicetypes.form')
        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Create Service Type</h4>

    <form method="POST" action="{{ route('service-types.store') }}">
        @csrf
        @include('in.admin.service_types._form')
        <button class="btn btn-success mt-3">Save</button>
    </form>
</div>
@endsection

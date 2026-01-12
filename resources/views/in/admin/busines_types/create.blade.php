@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Create Service Type</h4>

    <form method="POST" action="{{ route('busines-types.store') }}">
        @csrf
        @include('in.admin.busines_types._form')
        <button class="btn btn-success mt-3">Save</button>
    </form>
</div>
@endsection

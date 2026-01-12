@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Service Type</h4>

    <form method="POST" action="{{ route('busines-types.update', $businesType) }}">
        @csrf
        @method('PUT')
        @include('in.admin.busines_types._form')
        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Create Menu Category')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-plus-circle me-2 text-accent"></i> Create Menu Category
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('menu-categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('in.menu.menu_categories.form')
                <div class="text-end">
                    <button class="btn btn-accent">
                        <i class="bi bi-check-lg me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

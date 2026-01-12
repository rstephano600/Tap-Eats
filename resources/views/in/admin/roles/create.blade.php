@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-darkblue">Roles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Role</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-darkblue text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-shield-plus me-2 text-accent"></i> Role Registration
                    </h5>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf
                        
                        <div class="row">
                            @include('in.admin.roles.form')
                        </div>

                        <hr class="my-4 text-secondary opacity-25">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> All fields marked with * are required.</span>
                            <div class="d-flex gap-2">
                                <a href="{{ route('roles.index') }}" class="btn btn-light border px-4">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-accent px-5 shadow-sm fw-bold">
                                    <i class="bi bi-check2-circle me-1"></i> Save New Role
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-darkblue text-decoration-none">Roles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-darkblue text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square me-2 text-accent"></i> 
                        Edit Role: <span class="text-accent">{{ $role->name }}</span>
                    </h5>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('roles.update', $role) }}">
                        @csrf 
                        @method('PUT')
                        
                        <div class="row">
                            @include('in.admin.roles.form', ['role' => $role])
                        </div>

                        <hr class="my-4 text-secondary opacity-25">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="bi bi-clock-history me-1"></i> 
                                Last updated: {{ $role->updated_at ? $role->updated_at->diffForHumans() : 'Never' }}
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('roles.index') }}" class="btn btn-light border px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-accent px-5 shadow-sm fw-bold">
                                    <i class="bi bi-save me-1"></i> Update Changes
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
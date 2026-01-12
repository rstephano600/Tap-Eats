@extends('layouts.app')

@section('title', 'Create Permission')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}" class="text-darkblue">Permissions</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-darkblue text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-shield-plus me-2 text-accent"></i> Register New Permission</h5>
        </div>
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Permission Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                            <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g., user.create" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Account Status</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-toggle-on"></i></span>
                            <select name="status" class="form-select form-select-lg">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="locked">Locked</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-darkblue">Description</label>
                        <textarea name="descriptions" class="form-control" rows="3" placeholder="Explain what this permission allows..."></textarea>
                    </div>
                </div>

                <hr class="my-4 opacity-25">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('permissions.index') }}" class="btn btn-light border px-4">Cancel</a>
                    <button type="submit" class="btn btn-accent px-5 shadow-sm fw-bold">Save Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
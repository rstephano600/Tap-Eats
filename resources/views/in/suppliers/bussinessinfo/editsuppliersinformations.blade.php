@extends('layouts.app')

@section('title', 'Edit supplier')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('suppliersinformations') }}" class="text-darkblue">suppliers</a></li>
            <li class="breadcrumb-item active text-muted">Edit: {{ $supplier->name }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-darkblue text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-accent"></i> Edit supplier</h5>
        </div>
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('updatesuppliersinformations', encrypt($supplier->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">supplier Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                            <input type="text" name="name" value="{{ $supplier->name }}" class="form-control form-control-lg" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Status</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-activity"></i></span>
                            <select name="status" class="form-select form-select-lg">
                                <option value="active" {{ $supplier->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $supplier->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="locked" {{ $supplier->status == 'locked' ? 'selected' : '' }}>Locked</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-darkblue">Business logo Url <span class="text-danger">*</span></label>
                        <div class="input-group">
                        <input type="file" name="logo_url" class="form-control form-control-lg" accept="image/*">
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-darkblue">Business Gallery Images</label>
                        <input type="file" name="gallery_images[]" class="form-control form-control-lg" accept="image/*" multiple>
                    </div>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-darkblue">Description</label>
                        <textarea name="descriptions" class="form-control" rows="3">{{ $supplier->descriptions }}</textarea>
                    </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="d-flex justify-content-end gap-2">
                       <a href="{{ route('suppliersinformations') }}" class="btn btn-light border px-4">Cancel</a>
                       <button type="submit" class="btn btn-accent px-5 shadow-sm fw-bold">Update supplier</button>
                    </div>
            </form>
        </div>
    </div>
</div>
@endsection
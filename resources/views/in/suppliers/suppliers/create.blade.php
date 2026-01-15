@extends('layouts.app')

@section('title', 'Add Business')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-plus-circle me-2 text-accent"></i> Register New Business
            </h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('supplier.suppliers.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="business_name" class="form-control" required>
                    </div>

                   <div class="col-md-6">
    <label class="form-label fw-semibold">
        Business Type
    </label>

    <select name="business_type_id" class="form-select">
        <option value="">-- Select Business Type --</option>

        @foreach($businessTypes as $type)
            <option value="{{ $type->id }}"
                {{ old('business_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>

    <small class="text-muted">
        Select the category that best describes your business
    </small>
</div>


                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('supplier.suppliers.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button class="btn btn-accent">
                        <i class="bi bi-save me-1"></i> Save Business
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

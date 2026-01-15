@extends('layouts.app')

@section('title', 'Edit Business')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Business
            </h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('supplier.suppliers.update', $supplier) }}">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="business_name" class="form-control"
                               value="{{ $supplier->business_name }}" required>
                    </div>

                    <div class="col-md-6">
    <label class="form-label fw-semibold">
        Business Type
    </label>

    <select name="business_type_id" class="form-select">
        <option value="">-- Select Business Type --</option>

        @foreach($businessTypes as $type)
            <option value="{{ $type->id }}"
                {{ $supplier->business_type_id == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>


                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $supplier->description }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control"
                               value="{{ $supplier->contact_email }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control"
                               value="{{ $supplier->contact_phone }}" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('supplier.suppliers.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button class="btn btn-accent">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

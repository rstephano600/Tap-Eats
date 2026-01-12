@extends('layouts.app')

@section('title', 'Create supplier')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}" class="text-darkblue">suppliers</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-darkblue text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-shield-plus me-2 text-accent"></i> Register New supplier</h5>
        </div>
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Business Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <!-- <span class="input-group-text bg-light"><i class="bi bi-key"></i></span> -->
                            <input type="text" name="business_name" class="form-control form-control-lg" placeholder="e.g., business.create" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Business Type</label>
                        <select name="business_type_id" class="form-select form-select-lg @error('user_id') is-invalid @enderror" required>
                        <option value=" "> --- select business type --- </option>
                            @foreach($businesTypes as $businesType)
                            <option value="{{ $businesType->id }}">{{ $businesType->name }} </option>
                            @endforeach
                            </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Business Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <!-- <span class="input-group-text bg-light"><i class="bi bi-key"></i></span> -->
                            <input type="email" name="contact_email" class="form-control form-control-lg" placeholder="e.g., rstephano600@gmail.com" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-darkblue">Business Phone</label>
                            <input type="phone" name="contact_phone" class="form-control form-control-lg" placeholder="e.g., 0657856790" required>
                    </div>
                </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-darkblue">Description</label>
                        <textarea name="descriptions" class="form-control" rows="3" placeholder="Explain what this supplier allows..."></textarea>
                    </div>
                

                <hr class="my-4 opacity-25">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-light border px-4">Cancel</a>
                    <button type="submit" class="btn btn-accent px-5 shadow-sm fw-bold">Save supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
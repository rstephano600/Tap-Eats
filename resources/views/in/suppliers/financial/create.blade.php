@extends('layouts.app')

@section('title', 'Add Financial Info')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-plus-circle me-2 text-accent"></i> Add Financial Information
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('supplier.financial.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Supplier selector --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Select Business <span class="text-danger">*</span>
                        </label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->business_name }}{{ $supplier->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Commission Rate (%)</label>
                        <input type="number" step="0.01" name="commission_rate"
                               class="form-control" value="{{ old('commission_rate', 15.00) }}" required>
                        @error('commission_rate')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Bank Branch</label>
                        <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Account Name</label>
                        <input type="text" name="bank_account_name" class="form-control" value="{{ old('bank_account_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Account Number</label>
                        <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mobile Money Provider</label>
                        <input type="text" name="mobile_money_provider" class="form-control" value="{{ old('mobile_money_provider') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mobile Money Number</label>
                        <input type="text" name="mobile_money_number" class="form-control" value="{{ old('mobile_money_number') }}">
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_primary" value="1"
                                   {{ old('is_primary') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold">
                                Set as Primary Payment Method
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('supplier.financial.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-accent">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app') {{-- Change this to your actual layout --}}
@section('title', 'Create Location')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add New Supplier Location</h5>
                    <a href="{{ route('supplierlocations') }}" class="btn btn-sm btn-outline-light">Back to List</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('storesupplierlocations') }}" method="POST">
                        @csrf

                        {{-- Supplier Selection --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Select Supplier</label>
                                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">-- Choose a Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->business_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Location Name (Branch)</label>
                                <input type="text" name="location_name" class="form-control" value="{{ old('location_name') }}" placeholder="e.g. Downtown Branch" required>
                            </div>
                        </div>

                        {{-- Address Details --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address Line 2</label>
                                <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">State/Province</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country', 'USA') }}">
                            </div>
                        </div>

                        {{-- Contact & Coordinates --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control" placeholder="0.000000">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control" placeholder="0.000000">
                            </div>
                        </div>

                        <hr>

                        {{-- Status Toggles --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Location is Active</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary">
                                    <label class="form-check-label" for="is_primary">Set as Primary Location</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="reset" class="btn btn-light border">Reset</button>
                            <button type="submit" class="btn btn-dark px-5">Save Location</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
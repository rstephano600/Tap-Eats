@extends('layouts.app')

@section('title', 'Assign Supplier User')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-plus-circle me-2 text-accent"></i> Assign User To Supplier
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('storesuppuserinfo') }}" method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->business_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">User Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="full name.." required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" id="phone" class="form-control" placeholder="tapeats@gmail.com" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone Number</label>
                        <input type="phone" name="phone" id="phone" class="form-control" placeholder="+2556..." require>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_primary" class="form-check-input">
                            <label class="form-check-label">Primary Supplier</label>
                        </div>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('supplieruserinfo') }}" class="btn btn-light">Cancel</a>
                    <button class="btn btn-accent">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

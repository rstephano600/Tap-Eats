@extends('layouts.app')

@section('title', 'Edit Supplier User')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Assignment
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('updatesuppuserinfo', $supplierUser->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ $supplierUser->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->business_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $supplierUser->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $supplierUser->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="Status" class="form-select">
                            @foreach(['Active','Inactive','Locked','Deleted'] as $status)
                                <option value="{{ $status }}"
                                    {{ $supplierUser->Status == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_primary"
                                class="form-check-input"
                                {{ $supplierUser->is_primary ? 'checked' : '' }}>
                            <label class="form-check-label">Primary Supplier</label>
                        </div>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('supplieruserinfo') }}" class="btn btn-light">Cancel</a>
                    <button class="btn btn-accent">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

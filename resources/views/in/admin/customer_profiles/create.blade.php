@extends('layouts.app')

@section('title', 'Create Customer Profile')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-person-plus me-2"></i> New Customer Profile
        </div>

        <form method="POST" action="{{ route('customer-profiles.store') }}" class="card-body">
            @csrf

            <div class="mb-3">
                <label class="form-label">User</label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            @include('in.admin.customer_profiles.form')

            <div class="mt-4 text-end">
                <button class="btn btn-accent">
                    <i class="bi bi-save me-1"></i> Save Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

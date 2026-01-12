@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>My Addresses</h4>
        <a href="{{ route('customer.addresses.create') }}" class="btn btn-primary">
            + Add Address
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($addresses as $address)
            <div class="col-md-6 mb-3">
                <div class="card {{ $address->is_default ? 'border-success' : '' }}">
                    <div class="card-body">
                        <h6 class="fw-bold">
                            {{ $address->label ?? ucfirst($address->address_type) }}
                            @if($address->is_default)
                                <span class="badge bg-success">Default</span>
                            @endif
                        </h6>

                        <p class="mb-1">
                            {{ $address->address_line1 }}<br>
                            {{ $address->city }}, {{ $address->postal_code }}<br>
                            {{ $address->country }}
                        </p>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.addresses.edit', $address) }}"
                               class="btn btn-sm btn-outline-primary">Edit</a>

                            <form method="POST"
                                  action="{{ route('customer.addresses.destroy', $address) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Remove this address?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No addresses saved yet.</p>
        @endforelse
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Financial Info')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Financial Information
            </h5>
        </div>

        <div class="card-body">
            <form id="financialEditForm">
                @csrf @method('PUT')

                @include('in.suppliers.financial.form', ['info' => $financial])

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('supplier.financial.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button class="btn btn-accent">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('financialEditForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch("{{ route('supplier.financial.update', $financial->id) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: new FormData(this)
    }).then(() => window.location.href = "{{ route('supplier.financial.index') }}");
});
</script>
@endpush
@endsection

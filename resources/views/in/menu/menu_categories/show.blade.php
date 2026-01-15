@extends('layouts.app')

@section('title', 'View Menu Category')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-eye me-2 text-accent"></i> Menu Category Details
            </h5>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-md-3">Category Name</dt>
                <dd class="col-md-9">{{ $menuCategory->category_name }}</dd>

                <dt class="col-md-3">Supplier</dt>
                <dd class="col-md-9">{{ $menuCategory->supplier?->business_name ?? '—' }}</dd>

                <dt class="col-md-3">Description</dt>
                <dd class="col-md-9">{{ $menuCategory->description ?? '—' }}</dd>

                <dt class="col-md-3">Display Order</dt>
                <dd class="col-md-9">{{ $menuCategory->display_order }}</dd>

                <dt class="col-md-3">Status</dt>
                <dd class="col-md-9">
                    <span class="badge bg-{{ $menuCategory->is_active ? 'success' : 'secondary' }}">
                        {{ $menuCategory->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
                <dt class="col-md-3">Image</dt>
<dd class="col-md-9">
    @if($menuCategory->image)
        <img src="{{ asset('storage/' . $menuCategory->image) }}"
             class="rounded border"
             style="height:120px;">
    @else
        —
    @endif
</dd>

            </dl>
        </div>
    </div>
</div>
@endsection

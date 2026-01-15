@extends('layouts.app')

@section('title', 'Edit Variant')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Variant
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('menu-item-variants.update', $menuItemVariant->id) }}"
                  method="POST">
                @csrf @method('PUT')
                @include('in.menu.menu_item_variants.form', ['menuItemVariant' => $menuItemVariant])
                <div class="text-end">
                    <button class="btn btn-accent">
                        <i class="bi bi-check-lg me-1"></i> Update Variant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

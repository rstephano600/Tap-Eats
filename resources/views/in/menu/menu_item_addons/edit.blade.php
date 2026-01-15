@extends('layouts.app')

@section('title', 'Edit Add-on')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Add-on
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('menu-item-addons.update', $menuItemAddon->id) }}"
                  method="POST">
                @csrf @method('PUT')
                @include('in.menu.menu_item_addons.form', ['menuItemAddon' => $menuItemAddon])
                <div class="text-end">
                    <button class="btn btn-accent">
                        <i class="bi bi-check-lg me-1"></i> Update Add-on
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

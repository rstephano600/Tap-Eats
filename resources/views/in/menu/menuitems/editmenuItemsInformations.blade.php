@extends('layouts.app')

@section('title', 'Edit Menu Item')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Menu Item
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('updatemenuItemsInformations', encrypt($menuItem->id)) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @include('in.menu.menuitems.form', ['menuItem' => $menuItem])
                <div class="text-end">
                    <button class="btn btn-accent">
                        <i class="bi bi-check-lg me-1"></i> Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Menu Categories')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-tags me-2 text-accent"></i> Menu Categories
            </h5>
            <a href="{{ route('menu-categories.create') }}" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <!-- <th>Supplier</th> -->
                             <th>Image</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="fw-semibold text-darkblue">
                                    {{ $category->category_name }}
                                </td>

                                <!-- <td>
                                    {{ $category->supplier?->business_name ?? '—' }}
                                </td> -->
                                <td>@if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}"class="rounded" style="height:40px;">@else — @endif
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $category->display_order }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('menu-categories.show', $category->id) }}"
                                           class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('menu-categories.edit', $category->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('menu-categories.destroy', $category->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger"
                                                    onclick="return confirm('Delete this category?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No menu categories created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

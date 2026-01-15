@extends('layouts.app')

@section('title', 'Menu Item Variants')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-layers me-2 text-accent"></i> Menu Item Variants
            </h5>

            <a href="{{ route('menu-item-variants.create', request()->only('menu_item_id')) }}"
               class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Variant
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Menu Item</th>
                            <th>Variant</th>
                            <th>Price Adjustment</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($variants as $variant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="fw-semibold text-darkblue">
                                    {{ $variant->menuItem->name }}
                                </td>

                                <td>{{ $variant->variant_name }}</td>

                                <td>
                                    <span class="{{ $variant->price_adjustment >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $variant->price_adjustment >= 0 ? '+' : '' }}
                                        {{ number_format($variant->price_adjustment, 2) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $variant->is_available ? 'success' : 'secondary' }}">
                                        {{ $variant->is_available ? 'Yes' : 'No' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $variant->is_active ? 'success' : 'secondary' }}">
                                        {{ ucfirst($variant->status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('menu-item-variants.show', $variant->id) }}"
                                           class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('menu-item-variants.edit', $variant->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('menu-item-variants.destroy', $variant->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger"
                                                    onclick="return confirm('Delete this variant?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No variants found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $variants->links() }}
        </div>
    </div>
</div>
@endsection

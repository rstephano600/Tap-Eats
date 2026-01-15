@extends('layouts.app')

@section('title', 'Menu Item Add-ons')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-plus-square me-2 text-accent"></i> Menu Item Add-ons
            </h5>

            <a href="{{ route('menu-item-addons.create', request()->only('menu_item_id')) }}"
               class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Add-on
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Menu Item</th>
                            <th>Add-on</th>
                            <th>Price</th>
                            <th>Max Qty</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($addons as $addon)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="fw-semibold text-darkblue">
                                    {{ $addon->menuItem->name }}
                                </td>

                                <td>{{ $addon->addon_name }}</td>

                                <td>{{ number_format($addon->price, 2) }}</td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $addon->max_quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $addon->is_available ? 'success' : 'secondary' }}">
                                        {{ $addon->is_available ? 'Yes' : 'No' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $addon->is_active ? 'success' : 'secondary' }}">
                                        {{ ucfirst($addon->status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('menu-item-addons.show', $addon->id) }}"
                                           class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('menu-item-addons.edit', $addon->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('menu-item-addons.destroy', $addon->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger"
                                                    onclick="return confirm('Delete this add-on?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No add-ons found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $addons->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Menu Items')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-basket me-2 text-accent"></i> Menu Items
            </h5>
            <a href="{{ route('menu-items.create') }}" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Item
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    @if($item->image_url)
                                        <img src="{{ asset('storage/'.$item->image_url) }}"
                                             class="rounded"
                                             style="height:40px">
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="fw-semibold text-darkblue">
                                    {{ $item->name }}
                                    @if($item->is_featured)
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    @endif
                                </td>

                                <td>{{ $item->category?->category_name ?? '—' }}</td>

                                <td>
                                    <strong>{{ number_format($item->price, 2) }}</strong>
                                    @if($item->discounted_price)
                                        <br>
                                        <small class="text-success">
                                            {{ number_format($item->discounted_price, 2) }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{ $item->is_available ? 'success' : 'secondary' }}">
                                        {{ $item->is_available ? 'Yes' : 'No' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('menu-items.show', $item->id) }}"
                                           class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('menu-items.edit', $item->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('menu-items.destroy', $item->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger"
                                                    onclick="return confirm('Delete this item?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No menu items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Financial Information')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-bank me-2 text-accent"></i> Financial Information
            </h5>
            <a href="{{ route('supplier.financial.create') }}" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Financial Info
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Business</th>
                            <th>Payment Method</th>
                            <th>Commission</th>
                            <th>Primary</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($financials as $info)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
<td class="fw-semibold text-darkblue">
    {{ $info->supplier->business_name }}
</td>

                                <td>
                                    @if($info->bank_account_number)
                                        <strong>Bank</strong><br>
                                        <small class="text-muted">
                                            {{ $info->bank_name }} • ****{{ substr($info->bank_account_number, -4) }}
                                        </small>
                                    @else
                                        <strong>Mobile Money</strong><br>
                                        <small class="text-muted">
                                            {{ $info->mobile_money_provider }} • {{ $info->mobile_money_number }}
                                        </small>
                                    @endif
                                </td>

                                <td>{{ $info->commission_rate }}%</td>

                                <td>
                                    @if($info->is_primary)
                                        <span class="badge bg-success">Primary</span>
                                    @else
                                        <span class="badge bg-secondary">—</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{ $info->is_active ? 'success' : 'secondary' }}">
                                        {{ $info->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('supplier.financial.show', $info->id) }}"
                                           class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('supplier.financial.edit', $info->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No financial information added yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $financials->links() }}
        </div>
    </div>
</div>
@endsection

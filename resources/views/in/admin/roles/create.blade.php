@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-plus-circle"></i> Create New Role</h2>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Roles
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="e.g., customer, user" required>
                        <small class="text-muted">Use lowercase with underscores (e.g., customer_service)</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Display Name</label>
                        <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror" 
                               value="{{ old('display_name') }}" placeholder="e.g., Customer">
                        @error('display_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Brief description of this role">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Assign Permissions</h5>
                
                <div class="row">
                    @foreach($permissions as $category => $categoryPermissions)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input select-all-category" 
                                               id="select_all_{{ $category }}" 
                                               data-category="{{ $category }}">
                                        <label class="form-check-label fw-bold" for="select_all_{{ $category }}">
                                            {{ ucwords($category) }}
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach($categoryPermissions as $permission)
                                        <div class="form-check mb-2">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}" 
                                                   class="form-check-input permission-checkbox category-{{ $category }}" 
                                                   id="permission_{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="permission_{{ $permission->id }}">
                                                {{ str_replace('_', ' ', $permission->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Create Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all in category
    document.querySelectorAll('.select-all-category').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const checked = this.checked;
            document.querySelectorAll(`.category-${category}`).forEach(cb => {
                cb.checked = checked;
            });
        });
    });

    // Update select-all checkbox when individual checkboxes change
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const categoryClass = Array.from(this.classList).find(c => c.startsWith('category-'));
            if (categoryClass) {
                const category = categoryClass.replace('category-', '');
                const allCheckboxes = document.querySelectorAll(`.category-${category}`);
                const checkedCount = document.querySelectorAll(`.category-${category}:checked`).length;
                const selectAllCheckbox = document.querySelector(`#select_all_${category}`);
                
                selectAllCheckbox.checked = checkedCount === allCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
            }
        });
    });
});
</script>
@endsection
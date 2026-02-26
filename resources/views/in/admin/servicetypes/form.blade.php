<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $serviceType->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control">{{ old('description', $serviceType->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Features (comma separated)</label>
    <input type="text" name="features[]" class="form-control"
           value="{{ old('features', isset($serviceType) ? implode(',', $serviceType->features ?? []) : '') }}">
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-label">Display Order</label>
        <input type="number" name="display_order" class="form-control"
               value="{{ old('display_order', $serviceType->display_order ?? 0) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
            <option value="1" @selected(old('is_active', $serviceType->is_active ?? 1))>Yes</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['active','inactive','locked','deleted'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $serviceType->status ?? 'active') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

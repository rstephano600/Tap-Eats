<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-select">
            <option value="">— None —</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    @selected(old('supplier_id', $menuCategory->supplier_id ?? '') == $supplier->id)>
                    {{ $supplier->business_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Category Name *</label>
        <input type="text"
               name="category_name"
               class="form-control"
               value="{{ old('category_name', $menuCategory->category_name ?? '') }}"
               required>
    </div>

    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">
{{ old('description', $menuCategory->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Display Order</label>
        <input type="number"
               name="display_order"
               class="form-control"
               value="{{ old('display_order', $menuCategory->display_order ?? 0) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['active','inactive','locked'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $menuCategory->status ?? 'active') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input"
                   type="checkbox"
                   name="is_active"
                   value="1"
                   @checked(old('is_active', $menuCategory->is_active ?? true))>
            <label class="form-check-label">
                Active
            </label>
        </div>
    </div>
    <div class="col-md-6">
    <label class="form-label">Category Image</label>
    <input type="file" name="image" class="form-control">

    @if(!empty($menuCategory->image))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $menuCategory->image) }}"
                 class="rounded border"
                 style="height:80px;">
        </div>
    @endif
</div>

</div>

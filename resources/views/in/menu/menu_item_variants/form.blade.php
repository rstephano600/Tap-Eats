<div class="row g-3 mb-3">

    <div class="col-md-6">
        <label class="form-label">Menu Item *</label>
        <select name="menu_item_id" class="form-select" required>
            <option value="">— Select Item —</option>
            @foreach($menuItems as $item)
                <option value="{{ $item->id }}"
                    @selected(old('menu_item_id', $menuItemVariant->menu_item_id ?? request('menu_item_id')) == $item->id)>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Variant Name *</label>
        <input type="text"
               name="variant_name"
               class="form-control"
               value="{{ old('variant_name', $menuItemVariant->variant_name ?? '') }}"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Price Adjustment *</label>
        <input type="number"
               step="0.01"
               name="price_adjustment"
               class="form-control"
               value="{{ old('price_adjustment', $menuItemVariant->price_adjustment ?? 0) }}"
               required>
        <small class="text-muted">Use negative value to reduce price</small>
    </div>

    <div class="col-md-4">
        <label class="form-label">Display Order</label>
        <input type="number"
               name="display_order"
               class="form-control"
               value="{{ old('display_order', $menuItemVariant->display_order ?? 0) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['active','inactive','locked'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $menuItemVariant->status ?? 'active') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input"
                   type="checkbox"
                   name="is_available"
                   value="1"
                   @checked(old('is_available', $menuItemVariant->is_available ?? true))>
            <label class="form-check-label">Available</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input"
                   type="checkbox"
                   name="is_active"
                   value="1"
                   @checked(old('is_active', $menuItemVariant->is_active ?? true))>
            <label class="form-check-label">Active</label>
        </div>
    </div>

</div>

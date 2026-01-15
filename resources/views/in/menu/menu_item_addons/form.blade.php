<div class="row g-3 mb-3">

    <div class="col-md-6">
        <label class="form-label">Menu Item *</label>
        <select name="menu_item_id" class="form-select" required>
            <option value="">— Select Item —</option>
            @foreach($menuItems as $item)
                <option value="{{ $item->id }}"
                    @selected(old('menu_item_id', $menuItemAddon->menu_item_id ?? request('menu_item_id')) == $item->id)>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Add-on Name *</label>
        <input type="text"
               name="addon_name"
               class="form-control"
               value="{{ old('addon_name', $menuItemAddon->addon_name ?? '') }}"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Price *</label>
        <input type="number"
               step="0.01"
               name="price"
               class="form-control"
               value="{{ old('price', $menuItemAddon->price ?? '') }}"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Max Quantity *</label>
        <input type="number"
               name="max_quantity"
               class="form-control"
               min="1"
               value="{{ old('max_quantity', $menuItemAddon->max_quantity ?? 1) }}"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Display Order</label>
        <input type="number"
               name="display_order"
               class="form-control"
               value="{{ old('display_order', $menuItemAddon->display_order ?? 0) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['active','inactive','locked'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $menuItemAddon->status ?? 'active') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-8 d-flex align-items-center">
        <div class="form-check form-check-inline mt-4">
            <input class="form-check-input"
                   type="checkbox"
                   name="is_available"
                   value="1"
                   @checked(old('is_available', $menuItemAddon->is_available ?? true))>
            <label class="form-check-label">Available</label>
        </div>

        <div class="form-check form-check-inline mt-4">
            <input class="form-check-input"
                   type="checkbox"
                   name="is_active"
                   value="1"
                   @checked(old('is_active', $menuItemAddon->is_active ?? true))>
            <label class="form-check-label">Active</label>
        </div>
    </div>

</div>

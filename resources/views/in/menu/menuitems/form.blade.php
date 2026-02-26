<div class="row g-3 mb-3">

    {{-- Supplier & Category --}}
    <div class="col-md-6">
        <label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-select">
            <option value="">— None —</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    @selected(old('supplier_id', $menuItem->supplier_id ?? '') == $supplier->id)>
                    {{ $supplier->business_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="menu_category_id" class="form-select">
            <option value="">— None —</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('menu_category_id', $menuItem->menu_category_id ?? '') == $category->id)>
                    {{ $category->category_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Basic Info --}}
    <div class="col-md-6">
        <label class="form-label">Item Name *</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $menuItem->name ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Price *</label>
        <input type="number" step="0.01" name="price" class="form-control"
               value="{{ old('price', $menuItem->price ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Discounted Price</label>
        <input type="number" step="0.01" name="discounted_price" class="form-control"
               value="{{ old('discounted_price', $menuItem->discounted_price ?? '') }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3"
                  class="form-control">{{ old('description', $menuItem->description ?? '') }}</textarea>
    </div>

    {{-- Images --}}
    <div class="col-md-6">
        <label class="form-label">Main Image</label>
        <input type="file" name="image_url" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Gallery Images</label>
        <input type="file" name="gallery_images[]" multiple class="form-control">
    </div>

    {{-- Flags --}}
    <div class="col-md-12">
        @foreach([
            'is_vegetarian'=>'Vegetarian',
            'is_vegan'=>'Vegan',
            'is_gluten_free'=>'Gluten Free',
            'is_halal'=>'Halal',
            'is_spicy'=>'Spicy',
            'is_featured'=>'Featured',
            'is_popular'=>'Popular',
            'is_available'=>'Available'
        ] as $field => $label)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="{{ $field }}" value="1"
                       @checked(old($field, $menuItem->$field ?? false))>
                <label class="form-check-label">{{ $label }}</label>
            </div>
        @endforeach
    </div>

    {{-- Order & Status --}}
    <div class="col-md-4">
        <label class="form-label">Display Order</label>
        <input type="number" name="display_order" class="form-control"
               value="{{ old('display_order', $menuItem->display_order ?? 0) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['active','inactive','locked'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $menuItem->status ?? 'active') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

</div>

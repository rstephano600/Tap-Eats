<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Supplier;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * Display a listing of menu items.
     */
    public function index(Request $request)
    {
        $items = MenuItem::with(['supplier', 'category'])
            ->when($request->supplier_id, fn ($q) =>
                $q->where('supplier_id', $request->supplier_id)
            )
            ->when($request->category_id, fn ($q) =>
                $q->where('menu_category_id', $request->category_id)
            )
            ->orderBy('display_order')
            ->paginate(15);
            

        return view('in.menu.menu_items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        return view('in.menu.menu_items.create', [
            'suppliers'  => Supplier::orderBy('business_name')->get(),
            'categories' => MenuCategory::orderBy('category_name')->get(),
        ]);
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        /* Slug */
        $data['slug'] = Str::slug($data['name']);
        if (MenuItem::where('slug', $data['slug'])->exists()) {
            $data['slug'] .= '-' . uniqid();
        }

        /* Main image */
        if ($request->hasFile('image_url')) {
            $data['image_url'] = $request->file('image_url')
                ->store('menu_items', 'public');
        }

        /* Gallery images */
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('menu_items/gallery', 'public');
            }
            $data['gallery_images'] = $gallery;
        }

        $data['created_by'] = Auth::id();

        MenuItem::create($data);

        return redirect()
            ->route('menu-items.index')
            ->with('success', 'Menu item created successfully.');
    }

    /**
     * Display the specified item.
     */
    public function show(MenuItem $menuItem)
    {
        return view('in.menu.menu_items.show', compact('menuItem'));
    }

    /**
     * Show the form for editing.
     */
    public function edit(MenuItem $menuItem)
    {
        return view('in.menu.menu_items.edit', [
            'menuItem'   => $menuItem,
            'suppliers'  => Supplier::orderBy('business_name')->get(),
            'categories' => MenuCategory::orderBy('category_name')->get(),
        ]);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $this->validatedData($request, $menuItem->id);

        /* Slug update */
        if ($menuItem->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']);
            if (MenuItem::where('slug', $data['slug'])
                ->where('id', '!=', $menuItem->id)->exists()) {
                $data['slug'] .= '-' . uniqid();
            }
        }

        /* Image update */
        if ($request->hasFile('image_url')) {
            if ($menuItem->image_url) {
                Storage::disk('public')->delete($menuItem->image_url);
            }
            $data['image_url'] = $request->file('image_url')
                ->store('menu_items', 'public');
        }

        /* Gallery update */
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('menu_items/gallery', 'public');
            }
            $data['gallery_images'] = $gallery;
        }

        $data['updated_by'] = Auth::id();

        $menuItem->update($data);

        return redirect()
            ->route('menu-items.index')
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Soft delete.
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->update([
            'status'     => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        $menuItem->delete();

        return redirect()
            ->route('menu-items.index')
            ->with('success', 'Menu item deleted.');
    }

    /**
     * Restore soft-deleted item.
     */
    public function restore($id)
    {
        $item = MenuItem::onlyTrashed()->findOrFail($id);
        $item->restore();

        $item->update([
            'status'     => 'active',
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Menu item restored.');
    }

    /**
     * Force delete.
     */
    public function forceDelete($id)
    {
        $item = MenuItem::onlyTrashed()->findOrFail($id);

        if ($item->image_url) {
            Storage::disk('public')->delete($item->image_url);
        }

        if ($item->gallery_images) {
            foreach ($item->gallery_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $item->forceDelete();

        return back()->with('success', 'Menu item permanently deleted.');
    }

    /**
     * Validation rules (centralized).
     */
    protected function validatedData(Request $request, $id = null)
    {
        return $request->validate([
            'supplier_id'        => 'nullable|exists:suppliers,id',
            'menu_category_id'   => 'nullable|exists:menu_categories,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'discounted_price'   => 'nullable|numeric|min:0|lt:price',

            'image_url'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'preparation_time'   => 'nullable|integer|min:0',
            'serves'             => 'nullable|integer|min:1',
            'portion_size'       => 'nullable|string|max:50',
            'calories'           => 'nullable|integer|min:0',

            'is_vegetarian'      => 'boolean',
            'is_vegan'           => 'boolean',
            'is_gluten_free'     => 'boolean',
            'is_halal'           => 'boolean',
            'is_spicy'           => 'boolean',

            'allergens'          => 'nullable|array',
            'ingredients'        => 'nullable|array',
            'available_times'    => 'nullable|array',

            'is_available'       => 'boolean',
            'stock_quantity'     => 'nullable|integer|min:0',
            'is_featured'        => 'boolean',
            'is_popular'         => 'boolean',

            'display_order'      => 'nullable|integer|min:0',
            'is_active'          => 'boolean',
            'status'             => 'required|in:active,inactive,locked,deleted',
        ]);
    }
}


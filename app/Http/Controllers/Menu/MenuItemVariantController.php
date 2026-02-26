<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\MenuItemVariant;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuItemVariantController extends BaseController
{
    /**
     * Display a listing of variants (optionally per menu item).
     */
    public function index(Request $request)
    {
        $variants = MenuItemVariant::with('menuItem')
            ->when($request->menu_item_id, function ($q) use ($request) {
                $q->where('menu_item_id', $request->menu_item_id);
            })
            ->orderBy('display_order')
            ->paginate(15);

        return view('in.menu.menu_item_variants.index', compact('variants'));
    }

    /**
     * Show the form for creating a new variant.
     */
    public function create(Request $request)
    {
        return view('in.menu.menu_item_variants.create', [
            'menuItems' => MenuItem::orderBy('name')->get(),
            'menuItemId' => $request->menu_item_id,
        ]);
    }

    /**
     * Store a newly created variant.
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['created_by'] = Auth::id();

        MenuItemVariant::create($data);

        return redirect()
            ->route('menu-item-variants.index', ['menu_item_id' => $data['menu_item_id']])
            ->with('success', 'Menu item variant created successfully.');
    }

    /**
     * Display the specified variant.
     */
    public function show(MenuItemVariant $menuItemVariant)
    {
        return view('in.menu.menu_item_variants.show', compact('menuItemVariant'));
    }

    /**
     * Show the form for editing the specified variant.
     */
    public function edit(MenuItemVariant $menuItemVariant)
    {
        return view('in.menu.menu_item_variants.edit', [
            'menuItemVariant' => $menuItemVariant,
            'menuItems'       => MenuItem::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified variant.
     */
    public function update(Request $request, MenuItemVariant $menuItemVariant)
    {
        $data = $this->validatedData($request);

        $data['updated_by'] = Auth::id();

        $menuItemVariant->update($data);

        return redirect()
            ->route('menu-item-variants.index', ['menu_item_id' => $menuItemVariant->menu_item_id])
            ->with('success', 'Menu item variant updated successfully.');
    }

    /**
     * Soft delete the specified variant.
     */
    public function destroy(MenuItemVariant $menuItemVariant)
    {
        $menuItemVariant->update([
            'status'     => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        $menuItemVariant->delete();

        return back()->with('success', 'Menu item variant deleted.');
    }

    /**
     * Restore soft-deleted variant.
     */
    public function restore($id)
    {
        $variant = MenuItemVariant::onlyTrashed()->findOrFail($id);

        $variant->restore();
        $variant->update([
            'status'     => 'active',
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Menu item variant restored.');
    }

    /**
     * Permanently delete variant.
     */
    public function forceDelete($id)
    {
        $variant = MenuItemVariant::onlyTrashed()->findOrFail($id);
        $variant->forceDelete();

        return back()->with('success', 'Menu item variant permanently deleted.');
    }

    /**
     * Centralized validation rules.
     */
    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'menu_item_id'      => 'required|exists:menu_items,id',
            'variant_name'      => 'required|string|max:100',
            'price_adjustment'  => 'required|numeric',
            'is_available'      => 'boolean',
            'display_order'     => 'nullable|integer|min:0',
            'is_active'         => 'boolean',
            'status'            => 'required|in:active,inactive,locked,deleted',
        ]);
    }
}

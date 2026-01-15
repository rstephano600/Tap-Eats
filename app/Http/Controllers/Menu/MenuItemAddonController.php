<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;

use App\Models\MenuItemAddon;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuItemAddonController extends Controller
{
    /**
     * Display a listing of add-ons (optionally filtered by menu item).
     */
    public function index(Request $request)
    {
        $addons = MenuItemAddon::with('menuItem')
            ->when($request->menu_item_id, function ($q) use ($request) {
                $q->where('menu_item_id', $request->menu_item_id);
            })
            ->orderBy('display_order')
            ->paginate(15);

        return view('in.menu.menu_item_addons.index', compact('addons'));
    }

    /**
     * Show the form for creating a new add-on.
     */
    public function create(Request $request)
    {
        return view('in.menu.menu_item_addons.create', [
            'menuItems'  => MenuItem::orderBy('name')->get(),
            'menuItemId' => $request->menu_item_id,
        ]);
    }

    /**
     * Store a newly created add-on.
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['created_by'] = Auth::id();

        MenuItemAddon::create($data);

        return redirect()
            ->route('menu-item-addons.index', ['menu_item_id' => $data['menu_item_id']])
            ->with('success', 'Menu item add-on created successfully.');
    }

    /**
     * Display the specified add-on.
     */
    public function show(MenuItemAddon $menuItemAddon)
    {
        return view('in.menu.menu_item_addons.show', compact('menuItemAddon'));
    }

    /**
     * Show the form for editing the specified add-on.
     */
    public function edit(MenuItemAddon $menuItemAddon)
    {
        return view('in.menu.menu_item_addons.edit', [
            'menuItemAddon' => $menuItemAddon,
            'menuItems'     => MenuItem::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified add-on.
     */
    public function update(Request $request, MenuItemAddon $menuItemAddon)
    {
        $data = $this->validatedData($request);

        $data['updated_by'] = Auth::id();

        $menuItemAddon->update($data);

        return redirect()
            ->route('menu-item-addons.index', ['menu_item_id' => $menuItemAddon->menu_item_id])
            ->with('success', 'Menu item add-on updated successfully.');
    }

    /**
     * Soft delete the specified add-on.
     */
    public function destroy(MenuItemAddon $menuItemAddon)
    {
        $menuItemAddon->update([
            'status'     => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        $menuItemAddon->delete();

        return back()->with('success', 'Menu item add-on deleted.');
    }

    /**
     * Restore soft-deleted add-on.
     */
    public function restore($id)
    {
        $addon = MenuItemAddon::onlyTrashed()->findOrFail($id);

        $addon->restore();
        $addon->update([
            'status'     => 'active',
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Menu item add-on restored.');
    }

    /**
     * Permanently delete add-on.
     */
    public function forceDelete($id)
    {
        $addon = MenuItemAddon::onlyTrashed()->findOrFail($id);
        $addon->forceDelete();

        return back()->with('success', 'Menu item add-on permanently deleted.');
    }

    /**
     * Centralized validation rules.
     */
    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'menu_item_id'  => 'required|exists:menu_items,id',
            'addon_name'    => 'required|string|max:100',
            'price'         => 'required|numeric|min:0',
            'is_available'  => 'boolean',
            'max_quantity'  => 'required|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'is_active'     => 'boolean',
            'status'        => 'required|in:active,inactive,locked,deleted',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = MenuCategory::with('supplier')
            ->when($request->supplier_id, function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            })
            ->orderBy('display_order')
            ->paginate(15);

        return view('in.menu.menu_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('business_name')->get();

        return view('in.menu.menu_categories.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'category_name' => 'required|string|max:100',
            'description'   => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active'     => 'boolean',
            'status'        => 'required|in:active,inactive,locked,deleted',
        ]);
            if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')
            ->store('menu_categories', 'public');
    }


        $data['created_by'] = Auth::id();

        MenuCategory::create($data);

        return redirect()
            ->route('menu-categories.index')
            ->with('success', 'Menu category created successfully.');
    }
   

    /**
     * Display the specified resource.
     */
    public function show(MenuCategory $menuCategory)
    {
        return view('in.menu.menu_categories.show', compact('menuCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuCategory $menuCategory)
    {
        $suppliers = Supplier::orderBy('business_name')->get();

        return view('in.menu.menu_categories.edit', compact('menuCategory', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuCategory $menuCategory)
    {
        $data = $request->validate([
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'category_name' => 'required|string|max:100',
            'description'   => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active'     => 'boolean',
            'status'        => 'required|in:active,inactive,locked,deleted',
        ]);

            if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')
            ->store('menu_categories', 'public');
    }

        $data['updated_by'] = Auth::id();

        $menuCategory->update($data);

        return redirect()
            ->route('menu-categories.index')
            ->with('success', 'Menu category updated successfully.');
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(MenuCategory $menuCategory)
    {
        $menuCategory->update([
            'status'     => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        $menuCategory->delete();

        return redirect()
            ->route('menu-categories.index')
            ->with('success', 'Menu category deleted successfully.');
    }

    /**
     * Restore soft-deleted record.
     */
    public function restore($id)
    {
        $category = MenuCategory::onlyTrashed()->findOrFail($id);

        $category->restore();
        $category->update([
            'status'     => 'active',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('menu-categories.index')
            ->with('success', 'Menu category restored successfully.');
    }

    /**
     * Permanently delete.
     */
    public function forceDelete($id)
    {
        $category = MenuCategory::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return redirect()
            ->route('menu-categories.index')
            ->with('success', 'Menu category permanently deleted.');
    }
}

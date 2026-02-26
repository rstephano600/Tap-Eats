<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Supplier;
use App\Helpers\LogActivity;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends BaseController
{
    //MENU CATEGORIES METHODES

    //MENU ITEM METHODES
    public function menuItemsInformations(Request $request)
    {
        try{
            LogActivity::addToLog('View Menu Items Informations');
            $query = MenuItem::with(['supplier', 'category']);
            $this->scopeToSupplier($query);
            $items = $query->orderBy('display_order')->get();
                return view('in.menu.menuitems.menuItemsInformations', compact('items'));
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function createmenuItemsInformations()
    {
        try{
            LogActivity::addToLog('Create Menu Items Informations');

            $supplier = $this->getAuthSupplier();
            $suppliers = $supplier
                ? Supplier::where('id', $supplier->id)->orderBy('business_name')->get()
                : Supplier::orderBy('business_name')->get();
            $categories = $supplier
                ? MenuCategory::where('supplier_id', $supplier->id)->orderBy('category_name')->get()
                : MenuCategory::orderBy('category_name')->get();
                return view('in.menu.menuitems.createmenuItemsInformations', compact('suppliers', 'categories'));
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function storemenuItemsInformations(Request $request)
    {
        try{
            LogActivity::addToLog('Store Menu Items Informations');
            $data = $this->validatedData($request);
            $data['slug'] = Str::slug($data['name']);
            if (MenuItem::where('slug', $data['slug'])->exists()) {
                $data['slug'] .= '-' . uniqid();
            }
            if ($request->hasFile('image_url')) {
                $data['image_url'] = $request->file('image_url')
                    ->store('menuitems', 'public');
            }
            if ($request->hasFile('gallery_images')) {
                $gallery = [];
                foreach ($request->file('gallery_images') as $image) {
                    $gallery[] = $image->store('menuitems/gallery', 'public');
                }
                $data['gallery_images'] = $gallery;
            }
            $data['created_by'] = Auth::id();

            MenuItem::create($data);

            return redirect()->route('menuItemsInformations')->with('success' . auth()->user()->name, 'Supplier created successfully.');
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function showmenuItemsInformations($id)
    {
        try{
            LogActivity::addToLog('View Selected Menu Items Informations');
            $menuItem = MenuItem::findOrFail(decrypt($id));
            return view('in.menu.menuitems.showmenuItemsInformations', compact('menuItem'));
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function editmenuItemsInformations($id)
    {
        try{
            LogActivity::addToLog('Edit Selected Menu Items Informations');
            $menuItem   = MenuItem::findOrFail(decrypt($id));
            $supplier = $this->getAuthSupplier();
            $suppliers = $supplier
                ? Supplier::where('id', $supplier->id)->orderBy('business_name')->get()
                : Supplier::orderBy('business_name')->get();
            $categories = $supplier
                ? MenuCategory::where('supplier_id', $supplier->id)->orderBy('category_name')->get()
                : MenuCategory::orderBy('category_name')->get();
            return view('in.menu.menuitems.editmenuItemsInformations', compact('menuItem','suppliers','categories'));
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function updatemenuItemsInformations(Request $request, $id)
    {
        try{
            LogActivity::addToLog('Update Selected Menu Items Informations');
            $menuItem = MenuItem::findOrFail(decrypt($id));
            $data = $this->validatedData($request, $menuItem->id);
            if ($menuItem->name !== $data['name']) {
                $data['slug'] = Str::slug($data['name']);
                if (MenuItem::where('slug', $data['slug'])
                    ->where('id', '!=', $menuItem->id)->exists()) {
                    $data['slug'] .= '-' . uniqid();
                }
            }

            if ($request->hasFile('image_url')) {
                if ($menuItem->image_url) {
                    Storage::disk('public')->delete($menuItem->image_url);
                }
                $data['image_url'] = $request->file('image_url')
                    ->store('menu_items', 'public');
            }

            if ($request->hasFile('gallery_images')) {
                $gallery = [];
                foreach ($request->file('gallery_images') as $image) {
                    $gallery[] = $image->store('menu_items/gallery', 'public');
                }
                $data['gallery_images'] = $gallery;
            }

            $data['updated_by'] = Auth::id();
            $menuItem->update($data);

            return redirect()->route('menuItemsInformations')->with('success' . 'Congratraturation' . auth()->user()->name, 'Menu Item Updated  successfully.');
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

    public function destroymenuItemsInformations($id)
    {
        try{
            LogActivity::addToLog('Delete Selected Menu Items Informations');
            $menuItem = MenuItem::findOrFail(decrypt($id));
            $menuItem->update([
                'Status'     => 'Deleted',
                'updated_by' => Auth::id(),
                'deleted_at' => now(),
            ]);
            $menuItem->save();
            return redirect()->route('menuItemsInformations')->with('success' . 'Congratraturation' . auth()->user()->name, 'Menu Item Deleted  successfully.');
           } catch (\Throwable $e) {
               return back()
                   ->with('error', 'Technical error, please contact TapEats Administration for support. Tel: +255657856790');
           }
    }

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

            'is_vegetarian'      => 'nullable|boolean',
            'is_vegan'           => 'nullable|boolean',
            'is_gluten_free'     => 'nullable|boolean',
            'is_halal'           => 'nullable|boolean',
            'is_spicy'           => 'nullable|boolean',

            'allergens'          => 'nullable|array',
            'ingredients'        => 'nullable|array',
            'available_times'    => 'nullable|array',

            'is_available'       => 'nullable|boolean',
            'stock_quantity'     => 'nullable|integer|min:0',
            'is_featured'        => 'nullable|boolean',
            'is_popular'         => 'nullable|boolean',

            'display_order'      => 'nullable|integer|min:0',
            'is_active'          => 'nullable|boolean',

        ]);
    }
}
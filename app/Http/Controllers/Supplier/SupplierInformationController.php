<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\BusinessType;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class SupplierInformationController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('BusinessType')->where('status', '!=', 'deleted')
            ->latest()
            ->paginate(10);

        return view('in.suppliers.supplier_info.index', compact('suppliers'));
    }

    public function create()
    {
        $businesTypes = BusinessType::all();
        return view('in.suppliers.supplier_info.create', compact('businesTypes'));
    }

    public function store(Request $request)
    {
    //  try {
$request->validate([
    'business_name' => 'required|string|max:255',
    'business_type_id' => 'required|integer|exists:business_types,id',
    'contact_email' => 'required|email',
    'contact_phone' => 'required|string|max:20',
    'descriptions' => 'nullable|string|max:500',

    'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
    'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
]);

        $slug = Str::slug($request['business_name']);

    $logoPath = null;
    if ($request->hasFile('logo_url')) {
        $logoPath = $request->file('logo_url')
            ->store('suppliers/logos', 'public');
    }

    /** Upload cover image */
    $coverPath = null;
    if ($request->hasFile('cover_image')) {
        $coverPath = $request->file('cover_image')
            ->store('suppliers/covers', 'public');
    }

    /** Upload gallery images */
    $galleryPaths = [];
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $image) {
            $galleryPaths[] = $image->store('suppliers/gallery', 'public');
        }
    }

    Supplier::create([
        'business_name' => $request->business_name,
        'business_type_id' => $request->business_type_id,
        'contact_email' => $request->contact_email,
        'contact_phone' => $request->contact_phone,
        'slug' => $slug,
        'descriptions' => $request->descriptions,

        'logo_url' => $logoPath,
        'cover_image' => $coverPath,
        'gallery_images' => $galleryPaths ?: null,

        'user_id' => Auth::id(),
        'created_by' => Auth::id(),
        'status' => 'active',
    ]);

    return redirect()
        ->route('suppliers.index')
        ->with('success', 'Supplier created successfully.');

    // } catch (\Throwable $e) {
    //         Alert::error('Sorry! ' . auth()->user()->FirstName,'Technical error—please contact IT support.');
    //         return back();
    // }
    }

    public function show(Supplier $supplier)
    {
        return view('in.suppliers.supplier_info.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('in.suppliers.supplier_info.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
{
    $request->validate([
    'business_name' => 'required|string|max:255',
    'business_type_id' => 'required|integer|exists:business_types,id',
    'contact_email' => 'required|email',
    'contact_phone' => 'required|string|max:20',
    'descriptions' => 'nullable|string|max:500',

    'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
    'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
]);
    $slug = Str::slug($request->business_name);

    /** Logo */
    if ($request->hasFile('logo_url')) {
        $supplier->logo_url = $request->file('logo_url')
            ->store('suppliers/logos', 'public');
    }

    /** Cover image */
    if ($request->hasFile('cover_image')) {
        $supplier->cover_image = $request->file('cover_image')
            ->store('suppliers/covers', 'public');
    }

    /** Gallery images (append or replace) */
    if ($request->hasFile('gallery_images')) {
        $galleryPaths = $supplier->gallery_images ?? [];

        foreach ($request->file('gallery_images') as $image) {
            $galleryPaths[] = $image->store('suppliers/gallery', 'public');
        }

        $supplier->gallery_images = $galleryPaths;
    }

    $supplier->update([
        'business_name' => $request->business_name,
        'business_type_id' => $request->business_type_id,
        'contact_email' => $request->contact_email,
        'contact_phone' => $request->contact_phone,
        'slug' => $slug,
        'descriptions' => $request->descriptions,
    ]);

    return redirect()
        ->route('suppliers.index')
        ->with('success', 'Supplier updated successfully.');
}


    public function destroy(Supplier $supplier)
    {
        $supplier->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'supplier deleted successfully.');
    }
}



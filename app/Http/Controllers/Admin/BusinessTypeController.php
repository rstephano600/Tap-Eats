<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\BusinesType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BusinessTypeController extends Controller
{

    public function index()
    {
        $businesTypes = BusinesType::orderBy('display_order')->paginate(10);

        return view('in.admin.busines_types.index', compact('businesTypes'));
    }

    public function create()
    {
        return view('in.admin.busines_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'icon'          => 'nullable|string|max:255',
            'image'         => 'nullable|string|max:500',
            'features'      => 'nullable|array',
            'display_order' => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
            'status'        => 'required|in:active,inactive,locked,deleted',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['created_by'] = Auth::id();

        BusinesType::create($data);

        return redirect()
            ->route('busines-types.index')
            ->with('success', 'Service Type created successfully.');
    }

    public function show(BusinesType $businesType)
    {
        return view('in.admin.busines_types.show', compact('businesType'));
    }

    public function edit(BusinesType $businesType)
    {
        return view('in.admin.busines_types.edit', compact('businesType'));
    }

    public function update(Request $request, BusinesType $businesType)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'icon'          => 'nullable|string|max:255',
            'image'         => 'nullable|string|max:500',
            'features'      => 'nullable|array',
            'display_order' => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
            'status'        => 'required|in:active,inactive,locked,deleted',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['updated_by'] = Auth::id();

        $businesType->update($data);

        return redirect()
            ->route('busines-types.index')
            ->with('success', 'Service Type updated successfully.');
    }

    public function destroy(BusinesType $businesType)
    {
        $businesType->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('busines-types.index')
            ->with('success', 'Business Type deleted.');
    }
}


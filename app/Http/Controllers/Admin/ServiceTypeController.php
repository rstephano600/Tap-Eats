<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::orderBy('display_order')->paginate(10);

        return view('in.admin.service_types.index', compact('serviceTypes'));
    }

    public function create()
    {
        return view('in.admin.service_types.create');
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

        ServiceType::create($data);

        return redirect()
            ->route('service-types.index')
            ->with('success', 'Service Type created successfully.');
    }

    public function show(ServiceType $serviceType)
    {
        return view('in.admin.service_types.show', compact('serviceType'));
    }

    public function edit(ServiceType $serviceType)
    {
        return view('in.admin.service_types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
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

        $serviceType->update($data);

        return redirect()
            ->route('service-types.index')
            ->with('success', 'Service Type updated successfully.');
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);


        return redirect()
            ->route('service-types.index')
            ->with('success', 'Service Type deleted.');
    }
}

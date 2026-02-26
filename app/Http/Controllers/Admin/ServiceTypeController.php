<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ServiceTypeController extends Controller
{
    public function Servicetypes()
    {
        $serviceTypes = ServiceType::orderBy('display_order')->paginate(30);

        return view('in.admin.servicetypes.Servicetypes', compact('serviceTypes'));
    }

    public function createServicetypes()
    {
        return view('in.admin.servicetypes.createServicetypes');
    }

    public function storeServicetypes(Request $request)
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
            ->route('Servicetypes')
            ->with('success', 'Service Type created successfully.');
    }

    public function showServicetypes(ServiceType $serviceType)
    {
        return view('in.admin.servicetypes.showServicetypes', compact('serviceType'));
    }

    public function editServicetypes(ServiceType $serviceType)
    {
        return view('in.admin.servicetypes.editServicetypes', compact('serviceType'));
    }

    public function updateServicetypes(Request $request, ServiceType $serviceType)
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
            ->route('Servicetypes')
            ->with('success', 'Service Type updated successfully.');
    }

    public function destroyServicetypes(ServiceType $serviceType)
    {
        $serviceType->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('Servicetypes')
            ->with('success', 'Service Type deleted.');
    }
}

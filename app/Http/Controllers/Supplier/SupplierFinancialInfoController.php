<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierFinancialInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierFinancialInfoController extends Controller
{
    /**
     * List financial records (HTML)
     */
    public function index(Request $request)
    {
        $supplierId = $this->resolveSupplierId($request);

        $financials = SupplierFinancialInfo::where('supplier_id', $supplierId)
        ->where('status', 'active')
            ->latest()
            ->paginate(10);

        return view('in.suppliers.financial.index', compact('financials'));
    }

    /**
     * Show create form (HTML)
     */


public function create()
{
    $suppliers = Supplier::query()
        ->when(!auth()->user()->hasRole('super_admin'), function ($q) {
            $q->where('user_id', auth()->id());
        })
        ->where('status', 'active')
        ->where('is_active', true)
        ->orderBy('business_name')
        ->get();

    return view('in.suppliers.financial.create', compact('suppliers'));
}


    /**
     * Store financial info (JSON – AJAX)
     */
public function store(Request $request)
{
    $data = $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'commission_rate' => 'required|numeric|min:0|max:100',
        'bank_account_name' => 'nullable|string|max:255',
        'bank_account_number' => 'nullable|string|max:50',
        'bank_name' => 'nullable|string|max:100',
        'bank_branch' => 'nullable|string|max:100',
        'mobile_money_number' => 'nullable|string|max:20',
        'mobile_money_provider' => 'nullable|string|max:50',
        'is_primary' => 'nullable|boolean',
    ]);

    // Normalize checkbox
    $data['is_primary'] = $request->boolean('is_primary');

    // Ownership protection
    if (!auth()->user()->hasRole('super_admin')) {
        abort_unless(
            Supplier::where('id', $data['supplier_id'])
                ->where('user_id', auth()->id())
                ->exists(),
            403,
            'Unauthorized supplier'
        );
    }

    $financial = DB::transaction(function () use ($data) {

        if ($data['is_primary']) {
            SupplierFinancialInfo::where('supplier_id', $data['supplier_id'])
                ->update(['is_primary' => false]);
        }

        return SupplierFinancialInfo::create([
            'supplier_id' => $data['supplier_id'],
            'commission_rate' => $data['commission_rate'],
            'bank_account_name' => $data['bank_account_name'],
            'bank_account_number' => $data['bank_account_number'],
            'bank_name' => $data['bank_name'],
            'bank_branch' => $data['bank_branch'],
            'mobile_money_number' => $data['mobile_money_number'],
            'mobile_money_provider' => $data['mobile_money_provider'],
            'is_primary' => $data['is_primary'],
            'status' => 'active',
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);
    });

    return redirect()
        ->route('supplier.financial.index')
        ->with('success', 'Financial information added successfully.');
}



    /**
     * Show financial record (HTML)
     */
    public function show($id)
    {
        $financial = $this->findFinancial($id);

        return view('in.suppliers.financial.show', compact('financial'));
    }

    /**
     * Edit form (HTML)
     */

    public function edit($id)
{
    $financial = $this->findFinancial($id);

    $suppliers = Supplier::query()
        ->when(!auth()->user()->hasRole('super_admin'), function ($q) {
            $q->where('user_id', auth()->id());
        })
        ->orderBy('business_name')
        ->get();

    return view('in.suppliers.financial.edit', compact('financial', 'suppliers'));
}


    /**
     * Update financial info (JSON – AJAX)
     */
    public function update(Request $request, $id)
    {
        $financial = $this->findFinancial($id);

        $data = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'mobile_money_number' => 'nullable|string|max:20',
            'mobile_money_provider' => 'nullable|string|max:50',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($financial, $data) {
            if (!empty($data['is_primary'])) {
                SupplierFinancialInfo::where('supplier_id', $financial->supplier_id)
                    ->where('id', '!=', $financial->id)
                    ->update(['is_primary' => false]);
            }

            $financial->update([
                ...$data,
                'updated_by' => Auth::id(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Financial information updated successfully',
            'data' => $financial->fresh()
        ]);
    }

    /**
     * Set primary (JSON)
     */
    public function setPrimary($id)
    {
        DB::transaction(function () use ($id) {
            $financial = $this->findFinancial($id);

            SupplierFinancialInfo::where('supplier_id', $financial->supplier_id)
                ->update(['is_primary' => false]);

            $financial->update([
                'is_primary' => true,
                'updated_by' => Auth::id(),
            ]);
        });

        return response()->json(['success' => true]);
    }

    /**
     * Toggle active (JSON)
     */
    public function toggleActive($id)
    {
        $financial = $this->findFinancial($id);

        $financial->update([
            'is_active' => !$financial->is_active,
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'data' => $financial]);
    }

    /**
     * Soft delete (JSON)
     */
    public function destroy($id)
    {
        $financial = $this->findFinancial($id);

        $financial->update([
            'status' => 'deleted',
            'updated_by' => Auth::id(),
        ]);

        // $financial->delete();

        return response()->json(['success' => true]);
    }

    /* ===========================
       Helpers
       =========================== */

    private function resolveSupplierId(Request $request): int
    {
        if (auth()->user()->hasRole('super_admin') && $request->supplier_id) {
            return (int) $request->supplier_id;
        }

        return Supplier::where('user_id', auth()->id())->value('id');
    }

    private function findFinancial(int $id): SupplierFinancialInfo
    {
        return SupplierFinancialInfo::where('id', $id)
            ->whereIn(
                'supplier_id',
                Supplier::where('user_id', auth()->id())->pluck('id')
            )
            ->firstOrFail();
    }
}

<div class="row g-3">

    {{-- Supplier selector (read-only in edit) --}}
    <div class="col-md-6">
        <label class="form-label fw-bold">Business</label>
        <select class="form-select" disabled>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    {{ $supplier->id === $info->supplier_id ? 'selected' : '' }}>
                    {{ $supplier->business_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Commission Rate (%)</label>
        <input type="number" step="0.01" name="commission_rate"
               class="form-control"
               value="{{ old('commission_rate', $info->commission_rate) }}"
               required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Bank Name</label>
        <input type="text" name="bank_name" class="form-control"
               value="{{ old('bank_name', $info->bank_name) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Bank Branch</label>
        <input type="text" name="bank_branch" class="form-control"
               value="{{ old('bank_branch', $info->bank_branch) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Account Name</label>
        <input type="text" name="bank_account_name" class="form-control"
               value="{{ old('bank_account_name', $info->bank_account_name) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Account Number</label>
        <input type="text" name="bank_account_number" class="form-control"
               value="{{ old('bank_account_number', $info->bank_account_number) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Mobile Money Provider</label>
        <input type="text" name="mobile_money_provider" class="form-control"
               value="{{ old('mobile_money_provider', $info->mobile_money_provider) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Mobile Money Number</label>
        <input type="text" name="mobile_money_number" class="form-control"
               value="{{ old('mobile_money_number', $info->mobile_money_number) }}">
    </div>

    <div class="col-md-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_primary" value="1"
                   {{ old('is_primary', $info->is_primary) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold">
                Set as Primary Payment Method
            </label>
        </div>
    </div>

</div>

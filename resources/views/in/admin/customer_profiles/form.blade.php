<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control"
            value="{{ old('first_name', $customerProfile->first_name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control"
            value="{{ old('last_name', $customerProfile->last_name ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control"
            value="{{ old('date_of_birth', optional($customerProfile->date_of_birth ?? null)->format('Y-m-d')) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select">
            <option value="">-- Select --</option>
            @foreach(['male','female','prefer_not_to_say'] as $g)
                <option value="{{ $g }}"
                    @selected(old('gender', $customerProfile->gender ?? '') === $g)>
                    {{ ucfirst(str_replace('_',' ', $g)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['active','inactive','locked','deleted'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $customerProfile->status ?? 'active') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-darkblue">Role Name <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text bg-light text-darkblue"><i class="bi bi-person-badge"></i></span>
        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
               placeholder="e.g. System Administrator" 
               value="{{ old('name', $role->name ?? '') }}" required>
    </div>
    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-darkblue">Slug (URL Identifier)</label>
    <div class="input-group">
        <span class="input-group-text bg-light text-darkblue">
            <i class="bi bi-link-45deg"></i>
        </span>

        <input type="text"
               class="form-control form-control-lg"
               value="{{ $role->slug }}"
               disabled>
    </div>

    <!-- Send slug safely -->
    <input type="hidden" name="slug" value="{{ $role->slug }}">
</div>


<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-darkblue">Account Status <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text bg-light text-darkblue"><i class="bi bi-toggle-on"></i></span>
        <select name="status" class="form-select form-select-lg @error('status') is-invalid @enderror">
            @foreach(['active','inactive','locked','deleted'] as $status)
                <option value="{{ $status }}" 
                    {{ (old('status', $role->status ?? 'active') === $status) ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-darkblue">Short Description</label>
    <div class="input-group">
        <span class="input-group-text bg-light text-darkblue"><i class="bi bi-card-text"></i></span>
        <input type="text" name="descriptions" class="form-control form-control-lg" 
               placeholder="Briefly describe role permissions" 
               value="{{ old('descriptions', $role->descriptions ?? '') }}">
    </div>
</div>
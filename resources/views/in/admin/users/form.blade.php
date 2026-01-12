<div class="row">
    <div class="col-md-6 mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $user->name ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control"
               value="{{ old('username', $user->username ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $user->email ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control"
               value="{{ old('phone', $user->phone ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>User Type</label>
        <select name="user_type_id" class="form-select">
            <option value="">-- Select --</option>
            @foreach($userTypes as $type)
                <option value="{{ $type->id }}"
                    {{ old('user_type_id', $user->user_type_id ?? '') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
            @foreach(['active','inactive','suspended','locked'] as $status)
                <option value="{{ $status }}"
                    {{ old('status', $user->status ?? 'active') == $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Password {{ isset($user) ? '(Optional)' : '' }}</label>
        <input type="password" name="password" class="form-control">
    </div>

</div>

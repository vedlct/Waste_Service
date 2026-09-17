<div class="row g-4">
    <div class="col-lg-7">
        <h2 class="h5 fw-black mb-1">Account details</h2>
        <p class="text-muted mb-4">Create or update admin access for the panel.</p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="name">Name</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="role">Role</label>
                <select id="role" name="role" class="form-select select2 @error('role') is-invalid @enderror" required>
                    @foreach (['super_admin' => 'Super Admin', 'admin' => 'Admin', 'editor' => 'Editor'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="status">Status</label>
                <select id="status" name="status" class="form-select select2 @error('status') is-invalid @enderror" required>
                    @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $user->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" @if($mode === 'create') required @endif>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" @if($mode === 'create') required @endif>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="rounded-4 border bg-light p-4 h-100">
            <div class="metric-icon mb-3"><i class="bi bi-shield-lock-fill"></i></div>
            <h3 class="h5 fw-black">Access guidance</h3>
            <p class="text-muted">Use Super Admin only for people who should control the whole admin panel. Editors can later be limited to content modules as permissions expand.</p>
            @if ($mode === 'edit')
                <p class="small text-muted mb-0">Leave password fields blank to keep the current password.</p>
            @endif
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-end gap-2 border-top mt-4 pt-4">
    <a class="btn btn-outline-tee" href="{{ route('admin.users.index') }}">Cancel</a>
    <button class="btn btn-tee" type="submit"><i class="bi bi-check2 me-1"></i>Save User</button>
</div>

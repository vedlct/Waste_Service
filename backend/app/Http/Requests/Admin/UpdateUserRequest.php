<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'editor'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Guards against locking the panel out: nobody can change their own access, and the
     * last active super admin can never be demoted or deactivated.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $user = $this->route('user');

                if (! $user instanceof User) {
                    return;
                }

                $roleChanged = $this->input('role') !== $user->role;
                $statusChanged = $this->input('status') !== $user->status;

                if ($user->is($this->user()) && ($roleChanged || $statusChanged)) {
                    $validator->errors()->add('role', 'You cannot change your own role or status. Ask another super admin.');

                    return;
                }

                $losesSuperAdmin = $user->role === 'super_admin'
                    && $user->status === 'active'
                    && ($this->input('role') !== 'super_admin' || $this->input('status') !== 'active');

                if ($losesSuperAdmin && User::activeSuperAdminCount() <= 1) {
                    $validator->errors()->add('role', 'This is the last active super admin. Promote someone else first.');
                }
            },
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function data()
    {
        return DataTables::eloquent(User::query()->select(['id', 'name', 'email', 'role', 'status', 'created_at', 'last_login_at']))
            ->addColumn('account', function (User $user) {
                return view('admin.users.partials.account', compact('user'))->render();
            })
            ->editColumn('role', fn (User $user) => view('admin.users.partials.badge', [
                'label' => str($user->role)->headline(),
                'class' => 'role',
            ])->render())
            ->editColumn('status', fn (User $user) => view('admin.users.partials.badge', [
                'label' => str($user->status)->headline(),
                'class' => $user->status === 'active' ? 'active' : 'muted',
            ])->render())
            ->editColumn('last_login_at', fn (User $user) => $user->last_login_at?->format('d M Y, h:i A') ?? 'Never')
            ->editColumn('created_at', fn (User $user) => $user->created_at?->format('d M Y'))
            ->addColumn('actions', fn (User $user) => view('admin.users.partials.actions', compact('user'))->render())
            ->rawColumns(['account', 'role', 'status', 'actions'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.users.create', [
            'user' => new User(['role' => 'admin', 'status' => 'active']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['password'] = $request->string('password')->toString();

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $userId = $user?->id;
        $passwordRules = $user ? ['nullable', 'string', 'min:8', 'confirmed'] : ['required', 'string', 'min:8', 'confirmed'];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'editor'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'password' => $passwordRules,
        ]);
    }
}

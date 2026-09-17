<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.users.index');
    }

    public function data()
    {
        return $this->adminDataTable(User::query()->select(['id', 'name', 'email', 'role', 'status', 'created_at', 'last_login_at']))
            ->addColumn('account', fn (User $user) => $this->renderAdminPartial('admin.users.partials.account', compact('user')))
            ->editColumn('role', fn (User $user) => $this->renderStatusBadge(str($user->role)->headline(), 'role'))
            ->editColumn('status', fn (User $user) => $this->renderStatusBadge(
                str($user->status)->headline(),
                $user->status === 'active' ? 'active' : 'muted',
            ))
            ->editColumn('last_login_at', fn (User $user) => $this->formatAdminDate($user->last_login_at, 'd M Y, h:i A'))
            ->editColumn('created_at', fn (User $user) => $this->formatAdminDate($user->created_at, 'd M Y'))
            ->addColumn('actions', fn (User $user) => $this->renderAdminPartial('admin.users.partials.actions', compact('user')))
            ->rawColumns(['account', 'role', 'status', 'actions'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.users.create', [
            'user' => new User(['role' => 'admin', 'status' => 'active']),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = $request->string('password')->toString();

        User::create($data);

        return $this->success(redirect()->route('admin.users.index'), 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $this->success(redirect()->route('admin.users.index'), 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return $this->error(back(), 'You cannot delete your own account.');
        }

        $user->delete();

        return $this->success(redirect()->route('admin.users.index'), 'User deleted successfully.');
    }
}

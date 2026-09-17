<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfilePasswordRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;

class ProfileController extends Controller
{
    use FlashesMessages;

    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update($request->validated());

        return $this->success(redirect()->route('admin.profile.edit'), 'Profile updated successfully.');
    }

    public function updatePassword(UpdateProfilePasswordRequest $request)
    {
        $data = $request->validated();

        $request->user()->update([
            'password' => $data['password'],
        ]);

        return $this->success(redirect()->route('admin.profile.edit'), 'Password updated successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AdminStoreUserRequest;
use App\Http\Requests\User\AdminUpdateUserRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(
        AdminStoreUserRequest $request
    ) {
        $data = $request->validated();

        $user = User::create([
            ...$data,
            'password' => bcrypt($data['email']),
        ]);

        return back()->with([
            'flash' => [
                'type' => 'success',
                'message' => 'User created successfully',
            ],
            'user' => AdminUserResource::make($user)
        ]);
    }

    public function update(
        AdminUpdateUserRequest $request,
        User $user
    ) {
        $data = $request->validated();

        $user->update($data);

        return back()->with([
            'user' => AdminUserResource::make($user)
        ]);
    }

    public function destroy(
        User $user
    ) {
        $user->delete();

        return back()->with([
            'flash' => [
                'type' => 'success',
                'message' => 'User deleted successfully',
            ],
        ]);
    }
}

<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\LoginRequest;
use App\Api\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (
            !$user ||
            !Hash::check($request->password, $user->password) ||
            $user->role->slug != 'admin'
        ) {
            return response()->json([
                'message' => 'Login credentials are invalid'
            ], 403);
        }

        $token = $user->createToken('authToken', ['admin'])->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'User successfully logged out.'
        ]);
    }
}

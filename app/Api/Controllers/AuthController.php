<?php

namespace App\Api\Controllers;

use App\Api\Requests\LoginRequest;
use App\Api\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (
            !$user ||
            !Hash::check($request->password, $user->password)
        ) {
            return response()->json([
                'message' => 'Login credentials are invalid'
            ], 403);
        }

        $role = $user->role->slug;
        $ability = '';

        if ($role == 'admin') {
            $ability = 'admin';
        } else if ($role == 'cashier') {
            $ability = 'cashier';
        } else if ($role == 'finance') {
            $ability = 'finance';
        }

        $token = $user->createToken('authToken', [$ability])->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'ability' => $ability
        ]);
    }

    public function logout(Request $request)
    {
        // $request->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User successfully logged out.'
        ]);
    }
}

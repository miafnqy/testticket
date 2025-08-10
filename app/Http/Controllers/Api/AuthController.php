<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\SignupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $request->mergeIfMissing([
            'role_id' => UserRole::USER->value,
        ]);
        $user = User::create($request->only(['name', 'email', 'role_id', 'password']));

        return (new UserResource($user))->additional([
            'auth' =>
                [
                    'token' => $user->createToken('auth_token_for_'.$user->email)->plainTextToken
                ]
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }

        $user = User::firstWhere('email', $validated['email']);

        return response()->json([
            'message' => 'Authenticated successfully',
            'data' => [
                'token' => $user->createToken('auth_token_for_'.$user->email)->plainTextToken
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        if ($request->user()->cannot('create', [User::class, $request])) {
            return response()->json([], Response::HTTP_FORBIDDEN);
        }

        $request->mergeIfMissing([
            'password' => Hash::make('password')
        ]);
        $user = User::create($request->only(['name', 'email', 'role_id', 'password']));

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if($request->user()->canNot('update', $user))
        {
            return response()->json([], Response::HTTP_FORBIDDEN);
        }

        $user->update($request->only(['name', 'email']));

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if($request->user()->canNot('delete', $user))
        {
            return response()->json([], Response::HTTP_FORBIDDEN);
        }

        $user->delete();
    }
}

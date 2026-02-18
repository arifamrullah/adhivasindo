<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // return response()->json(User::all());

        return UserResource::collection(User::all());
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResource($user)
        ], 201);
    }

    public function show(User $user)
    {
        // return response()->json($user);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // $user->save();

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 204);
    }
}

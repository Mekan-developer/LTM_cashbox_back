<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\User\IndexResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(RegisterRequest $request, AuthService $service)
    {

        $result = $service->createUser($request);
        return response()->json($result, 201);
    }

    public function login(LoginRequest $request, AuthService $service)
    {
        $data = $request->validated();
        $result = $service->loginUser($data);
        return response()->json($result);
    }

    public function me(AuthService $service)
    {
        $result = $service->authUserWithRole();
        return response()->json($result);
    }

    public function logout(AuthService $service)
    {
        $result = $service->logoutAuthUser();
        return response()->json($result);
    }

    public function edit(User $user)
    {
        $user = new IndexResource($user->load('roles'));
        return response()->json([
            'user' => $user,
            'message' => 'user sended with roles'
        ]);
    }

    public function update(User $user, RegisterRequest $request, AuthService $service)
    {
        $result = $service->userUpdate($user, $request);

        return response()->json($result);
    }

    public function destroy(User $user, AuthService $service)
    {
        $result = $service->userDestroy($user);
        return response()->json($result);
    }
}

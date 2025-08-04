<?php

namespace App\Http\Controllers\Api;

use App\DTOs\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\User\IndexResource;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function users()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('title', 'admin');
        })->with('roles')->get();

        return response()->json(UserDTO::collection($users), 200);
    }


    public function edit(User $user)
    {
        $user = new IndexResource($user->load('roles'));
        return response()->json([
            'user' => $user,
            'message' => 'user sended with roles'
        ]);
    }

    public function update(User $user, RegisterRequest $request, UserService $service)
    {
        $result = $service->userUpdate($user, $request);

        return response()->json(['data' => $result['data']], 200);
    }

    public function destroy(User $user, UserService $service)
    {
        $result = $service->userDestroy($user);
        return response()->json($result, 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['token' => $user->createToken('api_token')->plainTextToken]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        \Log::info($data);

        $user = User::where('email', $request['email'])->first();

        if (!$user || !Hash::check($request['password'], $user->password)) {
            return response()->json(['message' => 'Неверный логин или пароль'], 401);
        }
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    public function me()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function logout()
    {
        $user = Auth::user();
        try {
            // $user->currentAccessToken()->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка при выходе из системы'], 500);
        }
        return response()->json(['message' => 'Вы вышли из системы']);
    }
}

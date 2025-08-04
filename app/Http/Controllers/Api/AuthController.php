<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}


    public function register(RegisterRequest $request, AuthService $service)
    {
        $dto = new RegisterDTO($request->validated());
        $result = $service->createUser($dto);
        return response()->json($result, 200);
    }

    public function login(LoginRequest $request, AuthService $service)
    {
        $dto = new LoginDTO($request->validated());
        $result = $service->loginUser($dto);
        return response()->json($result, 200);
    }

    public function me(AuthService $service)
    {
        $result = $service->authUserWithRole();
        return response()->json($result, 200);
    }

    public function logout(AuthService $service)
    {
        $result = $service->logoutAuthUser();

        // Можно добавить проверку по сообщению:
        $status = $result['message'] === 'Вы вышли из системы' ? 200 : 500;

        return response()->json($result, $status);
    }
}

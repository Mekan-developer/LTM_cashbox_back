<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}


    public function register(RegisterRequest $request)
    {
        $dto = new RegisterDTO($request->validated());
        $result = $this->service->createUser($dto);
        return response()->json($result, 200);
    }

    public function login(LoginRequest $request)
    {
        $dto = new LoginDTO($request->validated());
        $result = $this->service->loginUser($dto);
        return response()->json($result['data'], 201)->withCookie($result['cookie']);
    }

    // 🔁 Refresh
    public function refresh(Request $request)
    {
        Log::info('back end');
        return $this->service->refreshToken($request); // установить новый refresh token
    }


    public function me(AuthService $service)
    {
        $result = $service->authUserWithRole();
        return response()->json($result, 200);
    }

    public function logout(AuthService $service)
    {
        $result = $service->logoutAuthUser();

        return response()->json($result)->withCookie(
            cookie()->forget('refresh_token')
        );
    }
}

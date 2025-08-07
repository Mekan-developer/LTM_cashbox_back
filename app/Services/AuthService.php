<?php

namespace App\Services;

use App\Http\Resources\User\IndexResource;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(protected AuthRepository $repository) {}

    public function createUser($dto): array
    {
        $dto->password = Hash::make($dto->password);
        $user = $this->repository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password
        ]);
        $token = JWTAuth::fromUser($user);

        // Назначаем роль
        $role = $this->repository->getRoleById($dto->role_id);
        if ($role)
            $this->repository->attachRole($user, $role->id);


        return [
            'user' => $user,
            'token' => $token,
            'message' => 'Пользователь создан с ролью'
        ];
    }

    public function loginUser(Object $dto): array
    {

        $credentials =
            [
                'email' => $dto->email,
                'password' => $dto->password,
            ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return [
                ['error' => 'Unauthorized'],
                401
            ];
        }

        $refreshToken = JWTAuth::claims(['typ' => 'refresh'])->fromUser(Auth::user()); // 30 дней
        return $this->respondWithToken($token, $refreshToken);
    }

    public function refreshToken($request)
    {
        try {
            $refreshToken = $request->cookie('refresh_token');

            if (!$refreshToken) {
                return response()->json(['error' => 'Нет refresh токена'], 401);
            }

            // получить нового access token и нового refresh token
            $newAccessToken = JWTAuth::setToken($refreshToken)->refresh(true, true);
            $newRefreshToken = JWTAuth::claims(['typ' => 'refresh'])->fromUser(auth()->user());

            return response()->json([
                'success' => true,
                'user' => new IndexResource(Auth::user()),
                'token' => $newAccessToken,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ])->withCookie(
                cookie('refresh_token', $newRefreshToken, 43200, '/', null, true, true, false, 'None')
            );
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Ошибка обновления',
                'message' => $e->getMessage()
            ], 401);
        }
    }



    public function authUserWithRole()
    {
        $user = $this->repository->getAuthUserWithRole();

        return [
            'user' => $user
        ];
    }


    public function logoutAuthUser(): array
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ['message' => 'Выход выполнен'];
        } catch (JWTException $e) {
            return ['error' => 'Ошибка выхода'];
        }
    }

    protected function respondWithToken($token, $refreshToken = null)
    {
        return [
            'data' => [
                'success' => true,
                'user' => new IndexResource(Auth::user()),
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL()
            ],
            'cookie' => cookie('refresh_token', $refreshToken, 43200, '/', null, true, true, false, 'None')
        ];
    }
}

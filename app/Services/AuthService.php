<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
        $token = $user->createToken('api_token')->plainTextToken;

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

        if (!Auth::attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ])) {
            throw ValidationException::withMessages([
                'email' => ['Неверный логин или пароль'],
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'message' => 'Успешный вход',
        ];
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
        $user = $this->repository->getAuthUser();

        try {
            $user->tokens()->delete();
            return ['message' => 'Вы вышли из системы'];
        } catch (\Exception $e) {
            return ['message' => 'Ошибка при выходе из системы'];
        }
    }
}

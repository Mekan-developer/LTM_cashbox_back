<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function __construct(protected AuthRepository $repository) {}

    public function createUser(object $request): array
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);
        $token = $user->createToken('api_token')->plainTextToken;

        // Назначаем роль
        $role = $this->repository->getRoleById($data['role_id']);
        if ($role) {
            $this->repository->attachRole($user, $role->id);
        }


        return [
            'user' => $user,
            'token' => $token,
            'message' => 'Пользователь создан с ролью'
        ];
    }

    public function loginUser(array $data): array
    {
        $user = $this->repository->findByEmail($data);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [
                ['message' => 'Неверный логин или пароль'],
                401
            ];
        }
        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }

    public function authUserWithRole()
    {
        $user = $this->repository->getAuthUserWithRole();

        return [
            'user' => $user
        ];
    }


    public function logoutAuthUser()
    {
        $user = $this->repository->getAuthUser();
        try {
            $user->tokens()->delete();
            return [
                ['message' => 'Вы вышли из системы'],
                200
            ];
        } catch (\Exception $e) {
            return [
                ['message' => 'Ошибка при выходе из системы'],
                500
            ];
        }
    }

    public function userUpdate(User $user, Object $request)
    {
        $data = $request->validated();

        // Если передан пароль — хешируем
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Если пароль не передан — удаляем, чтобы не затирать старый
            unset($data['password']);
        }

        // Обновляем пользователя
        $user->update($data);

        // Назначаем роль
        $role = $this->repository->getRoleById($data['role_id']);
        if ($role) {
            // Заменяем все старые роли на одну новую
            $user->roles()->sync([$role->id]);
        }


        return [
            'user' => $user,
            'message' => 'Пользователь обновлён с ролью'
        ];
    }

    public function userDestroy(User $user): array
    {
        // Удаляем пользователя
        $user->delete();
        return [
            'message' => 'user deleted successfully!'
        ];
    }
}

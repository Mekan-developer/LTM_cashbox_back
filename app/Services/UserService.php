<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{

    public function __construct(protected UserRepository $repository) {}

    public function userUpdate(User $user, Object $request)
    {
        $data = $request->validated();

        if (!empty($data['password'])) // Если передан пароль — хешируем
            $data['password'] = Hash::make($data['password']);
        else
            unset($data['password']); // Если пароль не передан — удаляем, чтобы не затирать старый


        $user->update($data);
        $role = $this->repository->getRoleById($data['role_id']);
        if ($role)
            $user->roles()->sync([$role->id]); // Заменяем все старые роли на одну новую

        return [
            'data' => ['message' => 'Пользователь обновлён с ролью'],
        ];
    }

    public function userDestroy(User $user): array
    {
        try {
            $user->delete();
            return ['message' => 'User deleted successfully!'];
        } catch (\Exception $e) {
            Log::error('User deletion failed', ['error' => $e->getMessage()]);
            return ['message' => 'User deletion failed'];
        }
    }
}

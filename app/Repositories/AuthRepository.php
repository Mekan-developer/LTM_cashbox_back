<?php

namespace App\Repositories;

use App\Http\Resources\User\IndexResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function attachRole(User $user, int $roleId): void
    {
        $user->roles()->attach($roleId);
    }

    public function getRoleById(int $roleId): ?Role
    {
        return Role::find($roleId);
    }

    public function findByEmail(String $email): User
    {
        return User::where('email', $email)->first();
    }

    public function getAuthUserWithRole()
    {
        $user = Auth::user()->load('roles');
        $user = new IndexResource($user);
        return $user;
    }

    public function getAuthUser(): User
    {
        $user = Auth::user();
        return $user;
    }
}

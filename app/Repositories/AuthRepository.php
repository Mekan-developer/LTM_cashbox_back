<?php

namespace App\Repositories;

use App\Http\Resources\User\IndexResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;

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

    public function findByEmail(array $data): User
    {
        return User::where('email', $data['email'])->first();
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

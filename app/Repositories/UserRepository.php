<?php

namespace App\Repositories;


use App\Models\Role;

class UserRepository
{

    public function getRoleById(int $roleId): ?Role
    {
        return Role::find($roleId);
    }
}

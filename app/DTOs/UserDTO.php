<?php

namespace App\DTOs;

use App\Models\User;

class UserDTO
{
    public int $id;
    public string $name;
    public string $email;
    public array $roles;

    public function __construct(User $user)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = $user->roles->pluck('title')->toArray(); // Только названия ролей
    }

    public static function collection($users): array
    {
        return $users->map(fn($user) => new self($user))->toArray();
    }
}

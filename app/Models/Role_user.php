<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_user extends Model
{

    protected $table = 'Role_user';
    /** @use HasFactory<\Database\Factories\RoleUserFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'role_id'];
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Role_user;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['title' => 'admin'],    //mozhet spolzowat wse funcsiy
            ['title' => 'manager'], // ne mozhet udalit, mozhet udalit tolko swoy sozdanniy dannix
            ['title' => 'user'],   // tolko mozhet uwidet
        ];
        foreach ($datas as $data) {
            Role::UpdateOrCreate(['title' => $data['title']]);
        }

        Role_user::UpdateOrCreate(
            ['user_id' => 1],
            [
                'role_id' => 1
            ]
        );
    }
}

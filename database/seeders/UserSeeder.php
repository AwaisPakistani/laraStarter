<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
class UserSeeder extends Seeder
{
    public function run()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
 
        // Create Role
        $superadmin_role = Role::create([
            'name' => 'super_admin',
            'description' => 'Super Admin Role',
        ]);
        $superadmin->roles()->attach($superadmin_role);
    }
}

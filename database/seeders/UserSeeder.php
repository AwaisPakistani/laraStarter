<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
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
        ]);
        $superadmin->roles()->attach($superadmin_role);
        // make permissions
        $permissions = [
            'users.index',
            'users.create',
            'users.edit',
            'users.destroy',
            'users.show'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission,'guard_name' => 'web']);
        }
       
        // Admin Role 
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $admin_role = Role::create([
            'name' => 'admin',
        ]);
        $admin->roles()->attach($admin_role);

         // give permission to superadmin
        $superadmin_role->givePermissionTo(Permission::all());
    }
            
}

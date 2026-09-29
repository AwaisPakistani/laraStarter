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
        $users_permissions = [
            'users.index',
            'users.create',
            'users.edit',
            'users.destroy',
            'users.show',
        ];
         $roles_permissions = [
            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.destroy',
            'roles.show',
        ];
        $permissions_permissions = [
            'permissions.index',
            'permissions.create',
            'permissions.edit',
            'permissions.destroy',
            'permissions.show',
        ];
        foreach ($users_permissions as $permission) {
            Permission::create(['name' => $permission,'guard_name' => 'web']);
        }
        foreach ($roles_permissions as $permission) {
            Permission::create(['name' => $permission,'guard_name' => 'web']);
        }
        foreach ($permissions_permissions as $permission) {
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
        // gibe permissions to admin
        $admin_role->givePermissionTo($permissions_permissions);
    }

}
